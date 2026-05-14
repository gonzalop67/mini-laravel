<?php

namespace Core;

class MiniBlade
{
    protected array $sections = [];
    protected mixed $layout = null;
    protected string $viewsPath;
    protected string $cachePath;
    protected bool $useCache = true;

    public function __construct(string $viewsPath)
    {
        $this->viewsPath = rtrim($viewsPath, '/') . '/';
    }

    public function render(string $viewName, array $data = [])
    {
        $viewName = str_replace('.', '/', $viewName);

        // 1. Convertimos ['titulo' => 'Hola'] en $titulo
        // Usamos EXTR_SKIP para no sobreescribir variables internas de la función
        extract($data, EXTR_SKIP);

        $path = $this->viewsPath . $viewName . '.view.php';

        if (!file_exists($path)) {
            die("Error: La vista [$viewName] no existe en $path");
        }

        $content = file_get_contents($path);
        $compiled = $this->compile($content);

        ob_start();
        // Solo para probar:
        // return "<pre>" . htmlspecialchars($compiled) . "</pre>";
        // Usamos eval para ejecutar el PHP resultante de la compilación
        try {
            eval('?>' . $compiled);
        } catch (\Exception $e) {
            ob_end_clean();
            throw $e;
        }

        $output = ob_get_clean();

        if ($this->layout) {
            $parent = $this->layout;
            $this->layout = null;
            return $this->render($parent, $data);
        }

        return $output;
    }

    protected function compile(string $code)
    {
        // 1. Compilar variables {{ }} primero para evitar conflictos con directivas
        $code = preg_replace('/\{\{\s*(.*?)\s*\}\}/', '<?php echo htmlspecialchars((string)$1, ENT_QUOTES, "UTF-8"); ?>', $code);

        // 2. Directivas de Control con soporte para paréntesis anidados
        $code = preg_replace('/@if\s*\((.*)\)/', '<?php if($1): ?>', $code);
        $code = preg_replace('/@else/', '<?php else: ?>', $code);
        $code = preg_replace('/@endif/', '<?php endif; ?>', $code);

        $code = preg_replace('/@foreach\s*\((.*)\)/', '<?php foreach($1): ?>', $code);
        $code = preg_replace('/@endforeach/', '<?php endforeach; ?>', $code);

        // 3. Directiva @include
        $code = preg_replace('/@include\(\'(.*?)\'\)/', '<?php echo $this->includeView("$1", get_defined_vars()); ?>', $code);

        // 4. Captura de secciones limpia usando buffers de salida
        $code = preg_replace_callback('/@section\(\'(.*?)\'\)(.*?)@endsection/s', function ($m) {
            return "<?php ob_start(); ?>" . $m[2] . "<?php \$this->sections['" . $m[1] . "'] = ob_get_clean(); ?>";
        }, $code);

        // 5. Renderizado de @yield directo (Sin eval secundario)
        $code = preg_replace('/@yield\(\'(.*?)\'\)/', '<?php echo $this->sections["$1"] ?? ""; ?>', $code);

        // 6. Directiva @extends
        $code = preg_replace_callback('/@extends\(\'(.*?)\'\)/', function ($m) {
            $this->layout = $m[1];
            return '';
        }, $code);

        return $code;
    }


    protected function includeView(string $viewName, array $data): string
    {
        // Reutilizamos la lógica de renderizado para el parcial
        // pero sin permitir que el parcial use @extends (por simplicidad)
        $viewName = str_replace('.', '/', $viewName);
        $path = $this->viewsPath . $viewName . '.view.php';

        if (!file_exists($path)) {
            return "<!-- Error: Parcial [$viewName] no encontrado -->";
        }

        $content = file_get_contents($path);
        $compiled = $this->compile($content);

        // 1. Convertimos ['titulo' => 'Hola'] en $titulo
        // Usamos EXTR_SKIP para no sobreescribir variables internas de la función
        extract($data, EXTR_SKIP);

        // Solo para probar:
        // return "<pre>" . htmlspecialchars($compiled) . "</pre>";
        // return "<pre>" . print_r($data) . "</pre>";

        ob_start();
        try {
            eval('?>' . $compiled);
        } catch (\Exception $e) {
            ob_end_clean();
            throw $e;
        }

        $output = ob_get_clean();

        return $output;
    }
}
