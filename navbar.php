<!-- navbar.php -->
<nav class="bg-gray-800 p-4">
    <div class="container mx-auto flex justify-between items-center">
        <a href="#" class="text-white text-lg">Pathirana Motors</a>
        <ul class="flex space-x-4">
            <?php if (isset($customLinks)): ?>
                <?php foreach ($customLinks as $link): ?>
                    <li>
                        <a href="<?php echo $link['href']; ?>" class="text-white hover:text-gray-300">
                            <?php echo $link['title']; ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
    </div>
</nav>
