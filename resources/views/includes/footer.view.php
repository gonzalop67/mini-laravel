
    @if (isset($_SESSION['authenticated']))
    <div class="footer text-center">
        &copy; <a href="{{ $_SESSION['urlInstitucion'] }}" target="_blank">{{ $_SESSION['nombreInstitucion'] }}</a> Derechos Reservados
    </div>
    @endif
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>