<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

// Define custom links for the billing page
$customLinks = [
    'Add Item' => ['title' => 'Add Item', 'href' => 'add_item.php'],
    'About' => ['title' => 'About', 'href' => '#about'],
    'All Items' => ['title' => 'All Items', 'href' => 'view_items.php'],
    'Logout' => ['title' => 'Logout', 'href' => 'logout.php']
];

// Show logout button
$showLogout = true;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>Billing - Pathirana Motors</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .autocomplete-suggestions {
            border: 1px solid #ddd;
            background-color: #fff;
            position: absolute;
            z-index: 1000;
            max-height: 150px;
            overflow-y: auto;
        }
        .autocomplete-suggestion {
            padding: 8px;
            cursor: pointer;
        }
        .autocomplete-suggestion:hover {
            background-color: #f0f0f0;
        }
    </style>
</head>
<body class="bg-blue-900 min-h-screen flex flex-col">
    <?php include 'navbar.php'; ?>

    <div class="container mx-auto mt-8 flex-grow">
        <h1 class="text-3xl font-semibold text-white mb-4">Billing Page</h1>

        <!-- Billing Form -->
        <form id="billing-form" class="bg-gray-800 p-6 rounded shadow-md mb-6">
            <h2 class="text-2xl font-semibold text-white mb-4">Add Items to Bill</h2>

            <!-- Item Search Box -->
            <div class="relative mb-4">
                <label for="item-search" class="block text-white">Search Item</label>
                <input type="text" id="item-search" name="item-search" class="w-full p-2 border border-gray-600 rounded bg-gray-700 text-white" placeholder="Search for an item...">
                <div id="autocomplete-suggestions" class="autocomplete-suggestions hidden"></div>
            </div>

            <!-- Quantity Input -->
            <div class="mb-4">
                <label for="quantity" class="block text-white">Quantity</label>
                <input type="number" id="quantity" name="quantity" class="w-full p-2 border border-gray-600 rounded bg-gray-700 text-white" min="1" required>
            </div>

            <!-- Add Item Button -->
            <button type="button" id="add-item" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Add Item</button>
        </form>

        <!-- Items Table -->
        <div id="items-table" class="bg-gray-800 p-6 rounded shadow-md mb-6">
            <h2 class="text-2xl font-semibold text-white mb-4">Items Added</h2>
            <table class="w-full text-white">
                <thead>
                    <tr>
                        <th class="border-b px-4 py-2">Item</th>
                        <th class="border-b px-4 py-2">Quantity</th>
                        <th class="border-b px-4 py-2">Unit Price</th>
                        <th class="border-b px-4 py-2">Total Price</th>
                    </tr>
                </thead>
                <tbody id="items-body">
                    <!-- Rows will be added dynamically -->
                </tbody>
            </table>
            <div class="mt-4 text-right">
                <strong class="text-white">Total Price: Rs <span id="total-price">0</span></strong>
            </div>
        </div>

        <!-- Customer Details Form -->
        <form id="customer-details-form" class="bg-gray-800 p-6 rounded shadow-md mb-6">
            <h2 class="text-2xl font-semibold text-white mb-4">Customer Information</h2>
            <div class="mb-4">
                <label for="customer-name" class="block text-white">Customer Name</label>
                <input type="text" id="customer-name" name="customer-name" class="w-full p-2 border border-gray-600 rounded bg-gray-700 text-white" required>
            </div>
            <div class="mb-4">
                <label for="customer-phone" class="block text-white">Phone Number</label>
                <input type="text" id="customer-phone" name="customer-phone" class="w-full p-2 border border-gray-600 rounded bg-gray-700 text-white" required>
            </div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Generate Invoice</button>
        </form>
    </div>

    <?php include 'footer.php'; ?>

    <script>
        $(document).ready(function() {
            $('#item-search').on('input', function() {
                let query = $(this).val();

                if (query.length < 2) {
                    $('#autocomplete-suggestions').empty().addClass('hidden');
                    return;
                }

                $.ajax({
                    url: 'search.php',
                    type: 'GET',
                    data: { query: query },
                    success: function(data) {
                        const suggestions = $('#autocomplete-suggestions');
                        suggestions.empty().removeClass('hidden');

                        if (data.length > 0) {
                            data.forEach(item => {
                                const suggestionItem = $(`<div class="autocomplete-suggestion" data-id="${item.item_id}" data-price="${item.price}">${item.name} - Rs ${item.price}</div>`);
                                suggestions.append(suggestionItem);
                            });
                        } else {
                            suggestions.html('<div class="autocomplete-suggestion">No items found</div>');
                        }
                    },
                    error: function() {
                        Swal.fire({
                            title: 'Error!',
                            text: 'An error occurred while fetching suggestions.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });

            $(document).on('click', '.autocomplete-suggestion', function() {
                const itemName = $(this).text();
                const itemId = $(this).data('id');
                const itemPrice = $(this).data('price');

                $('#item-search').val(itemName);
                $('#item-search').data('id', itemId);
                $('#item-search').data('price', itemPrice);
                $('#autocomplete-suggestions').empty().addClass('hidden');
            });

            $('#add-item').on('click', function() {
                const itemName = $('#item-search').val();
                const itemId = $('#item-search').data('id');
                const itemPrice = $('#item-search').data('price');
                const quantity = parseInt($('#quantity').val());

                if (itemName && quantity > 0 && itemId && itemPrice) {
                    const totalPrice = itemPrice * quantity;

                    const row = $('<tr></tr>');
                    row.html(`
                        <td class="border-b px-4 py-2">${itemName}</td>
                        <td class="border-b px-4 py-2">${quantity}</td>
                        <td class="border-b px-4 py-2">Rs ${itemPrice}</td>
                        <td class="border-b px-4 py-2">Rs ${totalPrice}</td>
                    `);
                    $('#items-body').append(row);

                    // Update total price
                    let currentTotal = parseFloat($('#total-price').text());
                    currentTotal += totalPrice;
                    $('#total-price').text(currentTotal.toFixed(2));

                    // Clear the form
                    $('#item-search').val('');
                    $('#item-search').removeData('id').removeData('price');
                    $('#quantity').val('');
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Please select an item and enter a quantity.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            });

            $('#customer-details-form').on('submit', function(event) {
                event.preventDefault();
                Swal.fire({
                    title: 'Invoice Generated',
                    text: 'Your invoice has been generated successfully.',
                    icon: 'success',
                    confirmButtonText: 'OK'
                });
            });
        });
    </script>
</body>
</html>
