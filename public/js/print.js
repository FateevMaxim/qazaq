function printDiv(divId) {
    let divToPrint = document.getElementById(divId);
    let newWin = window.open('', 'Print-Window');
    newWin.document.open();
    newWin.document.write(`
        <html>
        <head>
            <style>
                /* Add any styles you want to apply to the printed content */
                body {
                    font-family: Arial, sans-serif;
                }
            </style>
        </head>
        <body>
            ${divToPrint.innerHTML}
            <script>
                window.onload = function() {
                    window.print();
                };
                window.onafterprint = function() {
                    window.close();
                };
            </script>
        </body>
        </html>
    `);
    newWin.document.close();
}
