function exportCSV() {
    var table = document.getElementById('myTable');
    var form = document.createElement('form');
    form.method = 'POST';
    form.action = '../scripts/export.php'; 
    var input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'csv_data';
    input.value = tableToCSV(table); 
    form.appendChild(input);
    document.body.appendChild(form);
    form.submit();
}
function tableToCSV(table) {
    var csv = '';
    var rows = table.getElementsByTagName('tr');
    var headerRow = rows[0];
    var headerCells = headerRow.getElementsByTagName('th');
    var headerData = [];
    for (var j = 0; j < headerCells.length; j++) {
      headerData.push(headerCells[j].innerText);
    }
    csv += headerData.join(',') + '\n';
    for (var i = 1; i < rows.length; i++) {
      var row = rows[i];
      var cells = row.getElementsByTagName('td');
      var rowData = [];
      for (var j = 0; j < cells.length; j++) {
        rowData.push(cells[j].innerText);
      }
      csv += rowData.join(',') + '\n';
    }
    return csv;
}
  