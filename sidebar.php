<button id="burger-menu" class="burger-menu fixed mt-2  ml-4  rounded hover:opacity-80">
    ☰
</button>
<div class="sidebar fixed top-0 left-[-250px] w-[250px] h-full  text-white p-5 transition-all ease-in-out duration-300 z-50">
    <h2 class="text-2xl mb-5 ml-8">Categories</h2>
    <ul>
        <li class="mb-3 pl-2"><a href="theme1.php" class="text-white hover:text-gray-400">1. Filing requirements and formalities</a></li>
        <li class="mb-3 pl-2"><a href="theme2.php" class="text-white hover:text-gray-400">2. Priority claims and right of priority</a></li>
        <li class="mb-3 pl-2"><a href="theme3.php" class="text-white hover:text-gray-400">3. Divisional applications</a></li>
        <li class="mb-3 pl-2"><a href="theme4.php" class="text-white hover:text-gray-400">4. Fees, payment methods, and time limits</a></li>
        <li class="mb-3 pl-2"><a href="theme5.php" class="text-white hover:text-gray-400">5. Languages and translations</a></li>
        <li class="mb-3 pl-2"><a href="theme6.php" class="text-white hover:text-gray-400">6. Procedural remedies and legal effect</a></li>
        <li class="mb-3 pl-2"><a href="theme7.php" class="text-white hover:text-gray-400">7. Pct procedure and entry into the european phase</a></li>
        <li class="mb-3 pl-2"><a href="theme8.php" class="text-white hover:text-gray-400">8. Examination, amendments, and grant</a></li>
        <li class="mb-3 pl-2"><a href="theme9.php" class="text-white hover:text-gray-400">9. Opposition and appeals</a></li>
        <li class="mb-3 pl-2"><a href="theme10.php" class="text-white hover:text-gray-400">10. Substantive patent law: novelty and inventive step</a></li>
        <li class="mb-3 pl-2"><a href="theme11.php" class="text-white hover:text-gray-400">11. Entitlement and transfers</a></li>
        <li class="mb-3 pl-2"><a href="theme12.php" class="text-white hover:text-gray-400">12. Biotech and sequence listings</a></li>
        <li class="mb-3 pl-2"><a href="theme13.php" class="text-white hover:text-gray-400">13. Unity of invention</a></li>
    </ul>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
    const burgerMenu = document.getElementById("burger-menu");
    const sidebar = document.querySelector(".sidebar");
    burgerMenu.addEventListener("click", function () {
        sidebar.classList.toggle("active");
        if (sidebar.classList.contains("active")) {
            burgerMenu.classList.add("active"); // Utilisez "active" ici
        } else {
            burgerMenu.classList.remove("active"); // Utilisez "active" ici
        }
    });
});
</script>

