function updateDoorDropdown() {
    var buildingDropdown = document.getElementById("jenis_properti");
    var doorDropdown = document.getElementById("jumlah_bilik");

    // Clear existing options
    doorDropdown.innerHTML = '<option disabled value="">Bilik</option>';

    // Get the selected building value
    var selectedBuilding = buildingDropdown.value;

    // Generate options for the selected building

    var dataProperti = <?php echo json_encode($dataProperti); ?>;
    console.log(dataProperti);

    dataProperti.forEach(element => {
        if (selectedBuilding === element.properti_id) {
            for (var i = 1; i <= element.jumlah_bilik; i++) {
                var option = document.createElement("option");
                option.text = "Bilik " + i;
                option.value = i;
                doorDropdown.add(option);
            }
        }
    });
}