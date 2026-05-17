<?php

namespace Core;

class MiniBlade
{
    protected array $sections = [];
    protected mixed $layout = null;
    protected string $viewsPath;
    protected array $sharedData = []; // 📦 Almacén de variables globales

    protected ?string $currentSection = null; // 🌟 SOLUCCIÓN: Declaración explícita para PHP 8.2+

    public function __construct(string $viewsPath)
    {
        $this->viewsPath = rtrim($viewsPath, '/') . '/';
    }

    /**
     * Registra variables globales para todas las vistas
     */
    public function share(string|array $key, mixed $value = null): void
    {
        if (is_array($key)) {
            $this->sharedData = array_merge($this->sharedData, $key);
        } else {
            $this->sharedData[$key] = $value;
        }
    }

    public function render(string $viewName, array $data = [])
    {
        $viewName = str_replace('.', '/', $viewName);

        // CORRECCIÓN 1: Fusionar datos globales ANTES de extraer las variables
        $combinedData = array_merge($this->sharedData, $data);
        extract($combinedData, EXTR_SKIP);

        $path = $this->viewsPath . $viewName . '.view.php';

        if (!file_exists($path)) {
            die("Error: La vista [$viewName] no existe en $path");
        }

        $content = file_get_contents($path);
        $compiled = $this->compile($content);

        ob_start();
        try {
            eval('?>' . $compiled);
        } catch (\Throwable $e) { // Captura Exception y ParseError en el eval
            ob_end_clean();
            throw $e;
        }

        $output = ob_get_clean();

        // Si la vista ejecutó la directiva @extends, $this->layout tendrá valor
        if ($this->layout) {
            $parent = $this->layout;
            $this->layout = null;
            return $this->render($parent, $combinedData);
        }

        return $output;
    }

    protected function compile(string $code)
    {
        // 1. Compilar variables {{ }} primero
        $code = preg_replace('/\{\{\s*(.*?)\s*\}\}/', '<?php echo htmlspecialchars((string)($1), ENT_QUOTES, "UTF-8"); ?>', $code);

        // 2. Directivas de Control
        $code = preg_replace('/@if\s*\((.*)\)/', '<?php if($1): ?>', $code);
        $code = preg_replace('/@else/', '<?php else: ?>', $code);
        $code = preg_replace('/@endif/', '<?php endif; ?>', $code);

        $code = preg_replace('/@foreach\s*\((.*)\)/', '<?php foreach($1): ?>', $code);
        $code = preg_replace('/@endforeach/', '<?php endforeach; ?>', $code);

        // 3. Directiva @include (Pasa todas las variables activas del contexto)
        $code = preg_replace('/@include\(\'(.*?)\'\)/', '<?php echo $this->includeView("$1", get_defined_vars()); ?>', $code);

        // CORRECCIÓN 3: Secciones dinámicas preparadas para eval sin romper bloques
        $code = preg_replace('/@section\(\'(.*?)\'\)/', '<?php ob_start(); \$this->currentSection = "$1"; ?>', $code);
        $code = preg_replace('/@endsection/', '<?php \$this->sections[\$this->currentSection] = ob_get_clean(); ?>', $code);

        // 5. Renderizado de @yield directo
        $code = preg_replace('/@yield\(\'(.*?)\'\)/', '<?php echo \$this->sections["$1"] ?? ""; ?>', $code);

        // CORRECCIÓN 2: @extends dinámico ejecutado dentro de eval, no en la compilación
        $code = preg_replace('/@extends\(\'(.*?)\'\)/', '<?php \$this->layout = "$1"; ?>', $code);

        return $code;
    }

    protected function includeView(string $viewName, array $data): string
    {
        $viewName = str_replace('.', '/', $viewName);
        $path = $this->viewsPath . $viewName . '.view.php';

        if (!file_exists($path)) {
            return "<!-- Error: Parcial [$viewName] no encontrado -->";
        }

        $content = file_get_contents($path);
        $compiled = $this->compile($content);

        // Inyectar variables globales también en los @include parciales
        $combinedData = array_merge($this->sharedData, $data);
        extract($combinedData, EXTR_SKIP);

        ob_start();
        try {
            eval('?>' . $compiled);
        } catch (\Throwable $e) {
            ob_end_clean();
            throw $e;
        }

        return ob_get_clean();
    }
}
