<div class="header fixed top-0 left-0 right-0  text-[#333] flex justify-between items-center p-12 shadow-md z-10">
<a href="#" class="header-btn flex justify-center items-center p-3 rounded-full bg-white shadow-md hover:opacity-80 mr-4 ml-12 relative" id="info-icon">
        <i class="fas fa-info-circle text-gray-700 text-xl"></i>
        <span class="tooltip-text absolute bg-white text-black text-sm rounded p-2 opacity-0 transition-opacity duration-300 left-1/2 transform -translate-x-1/2 top-full mb-2">
        Click on the burger menu (☰) to train on the theme you want.
        </span>
    </a>
    <h2 class="dataBattle absolute left-1/2 transform -translate-x-1/2 text-4xl font-bold text-gray-900 dark:text-white"> 
    <a href="index.php">Data Battle</a>
</h2>

    <div class="header-right flex items-center ml-auto">
        <button id="theme-toggle" class="header-btn flex justify-center items-center p-3 rounded-full bg-white shadow-md hover:opacity-80 ml-2">
            <i class="fas fa-moon text-gray-700 text-xl"></i>
        </button> 
        <a href="profil.php" class="header-btn flex justify-center items-center p-3 rounded-full bg-white shadow-md hover:opacity-80 ml-2">
            <i class="fas fa-user text-gray-700 text-xl"></i>
        </a>
        <a href="deconnexion.php" class="header-btn flex justify-center items-center p-3 rounded-full bg-white shadow-md hover:opacity-80 ml-2">
            <i class="fas fa-sign-out-alt text-gray-700 text-xl"></i>
        </a>
        
    </div>
</div>

<script>
        // Afficher la bulle au survol de l'icône d'info
        const infoIcon = document.getElementById('info-icon');
        const tooltipText = infoIcon.querySelector('.tooltip-text');
        
        infoIcon.addEventListener('mouseenter', function() {
            tooltipText.style.opacity = 1;  // Affiche la bulle d'information
        });
        
        infoIcon.addEventListener('mouseleave', function() {
            tooltipText.style.opacity = 0;  // Cache la bulle d'information
        });
</script>

<style>
    /* Style pour la bulle d'information (tooltip) */
    .tooltip-text {
        opacity: 0; /* Par défaut, la bulle est invisible */
        transition: opacity 0.3s ease-in-out;
        width : 150px;
    }

    .relative:hover .tooltip-text {
        opacity: 1; /* Au survol de l'élément, on rend la bulle visible */
    }
</style>

