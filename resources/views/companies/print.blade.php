<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Report</title>
    <!-- Load Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Use Inter font and ensure good print visibility */
        body {
            font-family: 'Inter', sans-serif;
            font-size: 12px;
            color: #1f2937; /* Gray-800 */
            padding: 20px;
        }

        /* Enforce table borders and structure for report-style */
        .report-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .report-table th, .report-table td {
            border: 1px solid #d1d5db; /* Gray-300 */
            padding: 8px 10px;
            text-align: left;
            font-size: 11px;
            vertical-align: top;
        }
        .report-table th {
            background-color: #f3f4f6; /* Gray-100 */
            font-weight: 600;
        }

        /* Signature block adjustments for display and print */
        .signature-box {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            min-width: 120px;
            font-size: 12px;
        }
        .signature-line {
            height: 1px;
            background-color: #000;
            margin-top: 60px; /* Space for signature */
            margin-bottom: 5px;
            width: 120px;
        }

        /* Print optimization */
        @media print {
            body {
                font-size: 10pt;
            }
            .report-table th, .report-table td {
                padding: 4px 6px;
                font-size: 9pt;
            }
        }
    </style>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                },
            },
        }

        // --- MOCK DATA ---
        const MOCK_CUSTOMERS = [
            { id: 1, name: "Alpha Corp Logistics", staff: "Wika Susanto", status: "Done / Closing", revenue: 250000, currency: "USD", last_fu: "2024-10-15", next_fu: "-", description: "Q3 acquisition project for new fleet management system completed." },
            { id: 2, name: "Beta Tech Solutions", staff: "Aulia Rahman", status: "Quotation send", revenue: 85000000, currency: "IDR", last_fu: "2024-10-20", next_fu: "2024-10-25", description: "Proposal for cloud migration; waiting for client finance approval." },
            { id: 3, name: "Gamma Holdings Inc.", staff: "Leni Hartono", status: "Follow up", revenue: 15000, currency: "USD", last_fu: "2024-10-21", next_fu: "2024-10-23", description: "Initial contact made via cold email. Client interested in a demo next week." },
            { id: 4, name: "Delta Marine", staff: "Wika Susanto", status: "On progress", revenue: 300000, currency: "EUR", last_fu: "2024-09-01", next_fu: "2024-11-05", description: "Negotiating service contract terms for 5 large vessels. Long sales cycle." },
            { id: 5, name: "Epsilon Ventures", staff: "Aulia Rahman", status: "Waiting approval", revenue: 45000, currency: "USD", last_fu: "2024-10-10", next_fu: "-", description: "Final proposal submitted. Expecting board decision this week." }
        ];

        // Helper function for date formatting
        function formatDate(date) {
            const options = { day: '2-digit', month: 'short', year: 'numeric' };
            return new Date(date).toLocaleDateString('en-GB', options).replace(/,/g, '');
        }

        // Helper function for currency formatting
        function formatRevenue(amount, currency) {
            // Simple localized formatting for the mock data
            const formattedAmount = amount.toLocaleString('en-US', { minimumFractionDigits: 0 });
            return `${currency} ${formattedAmount}`;
        }

        // Function to render the table
        function renderReport() {
            const now = new Date();
            const startOfMonth = new Date(now.getFullYear(), now.getMonth(), 1);
            const endOfMonth = new Date(now.getFullYear(), now.getMonth() + 1, 0);

            // Set dynamic dates in the header
            document.getElementById('report-period').textContent = `Period: ${formatDate(startOfMonth)} - ${formatDate(endOfMonth)}`;
            document.getElementById('report-generated').textContent = `Generated at: ${formatDate(now)} ${now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' })}`;

            const tbody = document.getElementById('customer-table-body');
            tbody.innerHTML = ''; // Clear existing content

            MOCK_CUSTOMERS.forEach((c, i) => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${i + 1}</td>
                    <td>${c.name}</td>
                    <td>${c.staff}</td>
                    <td>${c.status}</td>
                    <td>${formatRevenue(c.revenue, c.currency)}</td>
                    <td>${c.last_fu || '-'}</td>
                    <td>${c.next_fu || '-'}</td>
                    <td>${c.description || '-'}</td>
                `;
                tbody.appendChild(row);
            });
        }

        document.addEventListener('DOMContentLoaded', renderReport);
    </script>
</head>
<body class="mx-auto max-w-7xl">
    
    <!-- Report Header -->
    <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-1">Customer Report</h2>
        <p id="report-period" class="text-xs text-gray-600"></p>
        <p id="report-generated" class="text-xs text-gray-600"></p>
    </div>

    <!-- Customer Table -->
    <table class="report-table">
        <thead>
            <tr>
                <th class="w-10">No</th>
                <th class="w-40">Name</th>
                <th class="w-32">Assigned Staff</th>
                <th class="w-32">Status</th>
                <th class="w-32">Potential Revenue</th>
                <th class="w-24">Last Follow Up</th>
                <th class="w-24">Next Follow Up</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody id="customer-table-body">
            <!-- Table content dynamically inserted by JavaScript -->
            <tr><td colspan="8" class="text-center py-4 text-gray-500">Loading data...</td></tr>
        </tbody>
    </table>

    <!-- Signature Block -->
    <div class="flex flex-wrap justify-between mt-20 gap-x-4 gap-y-10">
        <div class="signature-box">
            <p class="font-medium text-gray-700">Prepared by:</p>
            <div class="signature-line"></div>
            <p class="font-bold">Admin</p>
        </div>
        <div class="signature-box">
            <p class="font-medium text-gray-700">Reviewed by:</p>
            <div class="signature-line"></div>
            <p class="font-bold">Marketing</p>
        </div>
        <div class="signature-box">
            <p class="font-medium text-gray-700">Reviewed by:</p>
            <div class="signature-line"></div>
            <p class="font-bold">Finance</p>
        </div>
        <div class="signature-box">
            <p class="font-medium text-gray-700">Acknowledged by:</p>
            <div class="signature-line"></div>
            <p class="font-bold">Manager</p>
        </div>
        <div class="signature-box">
            <p class="font-medium text-gray-700">Approved by:</p>
            <div class="signature-line"></div>
            <p class="font-bold">Director</p>
        </div>
    </div>
</body>
</html>
