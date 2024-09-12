<?php
$customLinks = isset($customLinks) ? $customLinks : [];
?>
<nav class="bg-gray-800 p-4 text-white flex justify-between items-center relative">
    <!-- Logo -->
    <div class="text-xl font-bold">
        Pathirana Motors
    </div>

    <!-- Hamburger Button for Mobile -->
    <div class="md:hidden relative">
        <button id="hamburger" class="focus:outline-none">
            <svg id="hamburger-icon" class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
            </svg>
        </button>

        <!-- Dropdown Menu for Mobile (hidden by default) -->
        <div id="dropdown-menu" class="hidden fixed top-16 right-4 w-48 rounded-lg shadow-lg z-50 backdrop-blur-lg bg-black bg-opacity-50 mt-2">
            <ul class="py-2">
                <?php foreach ($customLinks as $link): ?>
                    <li>
                        <a href="<?php echo $link['href']; ?>" class="block px-4 py-2 text-white hover:bg-gray-600 rounded-t">
                            <?php echo $link['title']; ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

    <!-- Navigation Links for Larger Screens -->
    <ul id="nav-links" class="hidden md:flex space-x-4">
        <?php foreach ($customLinks as $link): ?>
            <li><a href="<?php echo $link['href']; ?>" class="hover:text-gray-400"><?php echo $link['title']; ?></a></li>
        <?php endforeach; ?>
    </ul>
</nav>

<script>
    // Toggle the dropdown menu on click
    document.getElementById('hamburger').addEventListener('click', function () {
        const dropdownMenu = document.getElementById('dropdown-menu');
        const hamburgerIcon = document.getElementById('hamburger-icon');

        // Toggle dropdown visibility
        dropdownMenu.classList.toggle('hidden');

        // Toggle hamburger icon to a close icon when menu is open
        if (dropdownMenu.classList.contains('hidden')) {
            hamburgerIcon.innerHTML = `
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
            `;
        } else {
            hamburgerIcon.innerHTML = `
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            `;
        }
    });
</script>
