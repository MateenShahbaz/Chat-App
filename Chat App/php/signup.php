<?php
    session_start();
    include_once "config.php";
    $fname = mysqli_real_escape_string($conn, $_POST['fname']);
    $lname = mysqli_real_escape_string($conn, $_POST['lname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    if(!empty($fname) && !empty($lname) && !empty($email) && !empty($password)){
        // lets check user email is valid or not
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {// if email valid
            // lets check that email already exist in the database or not
            $sql = mysqli_query($conn, "SELECT email FROM users WHERE email = '{$email}'");
            if (mysqli_num_rows($sql) > 0) {
                echo "$email - Already exist";
            }else{
                // lets check user upload file or not
                if (isset($_FILES['image'])) { // if file is uplaoded
                    
                    $img_name = $_FILES['image']['name']; //getting user uplaoded img name
                    // $img_type = $_FILES['image']['type']; //getting user uplaod img type
                    $tmp_name = $_FILES['image']['tmp_name']; // getting temporary name is used to save/move filein our folder

                    //lets explode image and get the last extension like png jpg
                    $img_explode = explode('.', $img_name);
                    $img_ext = end($img_explode); // Here we get the extension of an user uplaoded img file
                    
                    $extensions = ['png', 'jpeg', 'jpg']; //these are some valid img extension and we have store them in array
                    if (in_array($img_ext, $extensions) === true) { // if user uplaoded img extension matched with any array extensions
                        $time = time(); // this will return time
                        // we need this time because when you uplaoding user img to in our folder we rename user file with current time 
                        // so all the image file will have unique name 

                        // lets move the user uplaoded img to our particular folder

                        $new_img_name = $time.$img_name;

                        if (move_uploaded_file($tmp_name, "images/".$new_img_name)) {    // if user uplaod img move to our folder successfully
                            $status = "Active Now"; // once user signed up then his status will be active
                            $random_id = rand(time(), 10000000); // craeting random id for user

                            //lets insert all user data inside table
                            $sql2 = mysqli_query($conn, "INSERT INTO users(unique_id, fname	, lname, email, password, img, status)
                                                VALUES ({$random_id}, '{$fname}', '{$lname}', '{$email}', '{$password}', '{$new_img_name}', '{$status}')");
                            
                            if ($sql2) { // if data is inserted
                                $sql3 = mysqli_query($conn, "SELECT * FROM users WHERE email = '{$email}'");
                                if (mysqli_num_rows($sql3) > 0) {
                                    $row = mysqli_fetch_assoc($sql3);
                                    $_SESSION['unique_id'] = $row['unique_id']; // using this session we used unique_id in other php files
                                    echo "success";
                                }
                            }else{
                                echo "Something went wrong";
                            }
                        }
                    }else{
                        echo "Please select an image file - jpeg, jpg, png!";
                    }


                }else{
                    echo "Please select an image";
                }
            }
            
        }else{
            echo "$email - This is not valid email";
        }
    }else{
        echo "All input field are required";
    }

?>