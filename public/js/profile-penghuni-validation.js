$(window).on("load", function () {
    document.querySelector("form").addEventListener("submit", function (event) {
        event.preventDefault(); // Prevent form submission

        var nikInput = document.getElementById("nik");
        var noKKInput = document.getElementById("no_kk");
        var namaInput = document.getElementById("name");
        var jenisKelaminInput = document.getElementById("jenis_kelamin");
        var noHandphoneInput = document.getElementById("no_handphone");
        var emailInput = document.getElementById("email");

        var nikError = document.getElementById("nik-error");
        var noKKError = document.getElementById("no_kk-error");
        var namaError = document.getElementById("name-error");
        var jenisKelaminError = document.getElementById("jenis_kelamin-error");
        var noHandphoneError = document.getElementById("no_handphone-error");
        var emailError = document.getElementById("email-error");

        var isValid = true;

        if (nikInput.value.length !== 16 || isNaN(nikInput.value)) {
            isValid = false;
            nikInput.classList.add("invalid");
            nikError.textContent = "NIK harus 16 digit dan berupa angka.";
        } else {
            nikInput.classList.remove("invalid");
            nikError.textContent = "";
        }

        if (noKKInput.value.length !== 16 || isNaN(noKKInput.value)) {
            isValid = false;
            noKKInput.classList.add("invalid");
            noKKError.textContent =
                "Nomor kartu keluarga harus 16 digit dan berupa angka";
        } else {
            noKKInput.classList.remove("invalid");
            noKKError.textContent = "";
        }

        if (namaInput.value === "") {
            isValid = false;
            namaInput.classList.add("invalid");
            namaError.textContent = "Nama harus diisi";
        } else {
            namaInput.classList.remove("invalid");
            namaError.textContent = "";
        }

        if (jenisKelaminInput.value === "") {
            isValid = false;
            jenisKelaminInput.classList.add("invalid");
            jenisKelaminError.textContent =
                "Jenis Kelamin harus dipilih";
        } else {
            jenisKelaminInput.classList.remove("invalid");
            jenisKelaminError.textContent = "";
        }

        if (noHandphoneInput.value === "" || isNaN(noHandphoneInput.value)) {
            isValid = false;
            noHandphoneInput.classList.add("invalid");
            noHandphoneError.textContent =
                "No Handphone must be a number and cannot be empty.";
        } else {
            noHandphoneInput.classList.remove("invalid");
            noHandphoneError.textContent = "";
        }

        if (emailInput.value === "" || !isValidEmail(emailInput.value)) {
            isValid = false;
            emailInput.classList.add("invalid");
            emailError.textContent =
                "Email must be a valid email format and cannot be empty.";
        } else {
            emailInput.classList.remove("invalid");
            emailError.textContent = "";
        }

        if (isValid) {
            // Form is valid, proceed with submission or further processing
            console.log("Form submitted successfully!");
        }
    });

    function isValidEmail(email) {
        // Basic email format validation using regular expression
        var emailRegex = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
        return emailRegex.test(email);
    }
});
