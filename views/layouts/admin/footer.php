<!-- Script JavaScript Global Admin -->
    <script>
        let isCollapsed = false;

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const brandContainer = document.getElementById('brand-container');
            const menuLabels = document.querySelectorAll('.menu-label');
            const userInfo = document.querySelector('.user-info');
            const collapseIcon = document.getElementById('collapse-icon');

            isCollapsed = !isCollapsed;

            if (isCollapsed) {
                sidebar.style.width = '80px';
                brandContainer.style.opacity = '0';
                setTimeout(() => brandContainer.style.display = 'none', 150);
                menuLabels.forEach(label => label.style.display = 'none');
                userInfo.style.display = 'none';
                collapseIcon.classList.remove('fa-chevron-left');
                collapseIcon.classList.add('fa-chevron-right');
            } else {
                sidebar.style.width = '256px';
                brandContainer.style.display = 'flex';
                setTimeout(() => brandContainer.style.opacity = '1', 50);
                menuLabels.forEach(label => label.style.display = 'inline');
                userInfo.style.display = 'block';
                collapseIcon.classList.remove('fa-chevron-right');
                collapseIcon.classList.add('fa-chevron-left');
            }
        }

        function openModal(modalId) {
            document.getElementById(modalId).classList.remove('hidden');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }
    </script>
</body>
</html>