<?php
    $server = '18.139.72.14';
    $db = 'evoria';
    $username = 'bummi';
    $password = 'plokijuh';

    $server2 = '18.139.72.14';
    $db2 = 'mbloc';
    $username2 = 'postgres';
    $password2 = 'plokijuh';

    $conn = new mysqli($server, $username, $password, $db);
    $conn2 = pg_connect("host='".$server2."' dbname='".$db2."' user='".$username2."' password='".$password2."'");

    if ($conn->connect_error){
        die("Connection failed: ".$conn->connect_error);
    }

    if (!$conn2) {
        die("Connection failed: ".$conn2->connect_error);
    }

    $submissionQuery = "SELECT b.meta_value as email, c.meta_value as ktp, d.meta_value as name, e.meta_value as jabatan, f.meta_value as phone,
        g.meta_value as star_name, h.meta_value as format_tampilan, i.meta_value as jumlah_personil, q.meta_value as label,
        m.meta_value as link_sosmed, k.meta_value as star_address, n.meta_value as link_karya, o.meta_value as foto_penampil,
        p.meta_value as logo_penampil, j.meta_value as genre ,l.meta_value as profil_singkat, a.entry_id
            FROM evoria_wp_frmt_form_entry as a
                JOIN evoria_wp_frmt_form_entry_meta as b ON a.entry_id = b.entry_id
                JOIN evoria_wp_frmt_form_entry_meta as c ON a.entry_id = c.entry_id
                JOIN evoria_wp_frmt_form_entry_meta as d ON a.entry_id = d.entry_id
                JOIN evoria_wp_frmt_form_entry_meta as e ON a.entry_id = e.entry_id
                JOIN evoria_wp_frmt_form_entry_meta as f ON a.entry_id = f.entry_id
                JOIN evoria_wp_frmt_form_entry_meta as g ON a.entry_id = g.entry_id
                JOIN evoria_wp_frmt_form_entry_meta as h ON a.entry_id = h.entry_id
                JOIN evoria_wp_frmt_form_entry_meta as i ON a.entry_id = i.entry_id
                JOIN evoria_wp_frmt_form_entry_meta as j ON a.entry_id = j.entry_id
                JOIN evoria_wp_frmt_form_entry_meta as k ON a.entry_id = k.entry_id
                JOIN evoria_wp_frmt_form_entry_meta as l ON a.entry_id = l.entry_id
                JOIN evoria_wp_frmt_form_entry_meta as m ON a.entry_id = m.entry_id
                JOIN evoria_wp_frmt_form_entry_meta as n ON a.entry_id = n.entry_id
                JOIN evoria_wp_frmt_form_entry_meta as o ON a.entry_id = o.entry_id
                JOIN evoria_wp_frmt_form_entry_meta as p ON a.entry_id = p.entry_id
                JOIN evoria_wp_frmt_form_entry_meta as q ON a.entry_id = q.entry_id
            WHERE b.meta_key = 'name-1' and
                c.meta_key = 'name-2' and
                d.meta_key = 'name-3' and
                e.meta_key = 'name-10' and
                f.meta_key = 'phone-1' and
                g.meta_key = 'name-4' and
                h.meta_key = 'radio-1' and
                i.meta_key = 'name-5' and
                j.meta_key = 'name-6' and
                k.meta_key = 'name-7' and
                l.meta_key = 'name-8' and
                m.meta_key = 'url-1' and
                n.meta_key = 'url-2' and
                o.meta_key = 'upload-1' and
                p.meta_key = 'upload-2' and
                q.meta_key = 'name-9' and
                a.entry_id > '267'
            ORDER BY a.entry_id ";

    $res_submission_form = mysqli_query($conn, $submissionQuery);

    if (mysqli_num_rows($res_submission_form) > 0) {
        while($row = mysqli_fetch_assoc($res_submission_form)) {
            $phone = preg_replace('/\s/', '', $row);

            if(!preg_match('/[^+0-9]/',trim($phone))){
                if(substr(trim($phone), 0, 2)=='62'){
                    $phone = trim($phone);
                }
                elseif(substr(trim($phone), 0, 1)=='0'){
                    $phone = '62'.substr(trim($phone), 1);
                }
            }

            $user_apps_query = 'SELECT * FROM user_apps WHERE phone ='.$val->phone;
            $res_user_apps = pg_query($conn2, $user_apps_query);
            if (pg_num_rows($res_user_apps) > 0) {
                while ($row_user_apps = pg_fetch_row($res_user_apps)) {
                    $phone = $row_user_apps['phone'];
                    $user_apps_id = $row_user_apps['id'];
                }
            }else {
                $user_apps_query2 = 'SELECT * FROM user_apps WHERE email ='.$val->email;
                $res_user_apps2 = pg_query($conn2, $user_apps_query2);
                if (pg_num_rows($res_user_apps2) > 0) {
                    while($row_user_apps2 = pg_fetch_row($res_user_apps2)) {
                        $explodeEmail = explode('@'. $row_user_apps2['email']);
                        $email = $expldoeEmail[0].$row['entry_id'].$expldoeEmail[1];
                    }
                }

                $query_insert_user_apps = 'INSERT INTO user_apps (phone, name, email, ktp, user_category) VALUES ('.$phone.', '.$row['name'].', '.$email.', '.$row['ktp'].', evoria)';
                $res_user_apps = pg_query($conn2, $query_insert_user_apps);
                if ($res_user_apps) {
                    $user_apps_id = pg_last_oid($res_user_apps);
                }

            }

            $query_types = 'SELECT * FROM types a WHERE lower(a.name) = '.strtolower($row['format_tampilan']);
            $res_query_types = pg_query($conn2, $query_types);
            $type_id = 0;
            if (pg_num_rows($res_query_types) > 0) {
                while($row_types = pg_fetch_row($res_query_types)) {
                    $type_id = $row_types['id'];
                }
            }else {
                $query_insert_types = "INSERT INTO types (name) VALUES (".$row['format_tampilan'].")";
                $res_query_insert_types = pg_query($conn2, $query_insert_types);
                $type_id = pg_last_oid($res_query_insert_types);
            }

            $query_insert_contestan = "INSERT INTO contestan (name, biodata, type_id, status, jumlah_penampil, address,
                link_social_media, link_audio_video, nama_management, ktp, email, style_music, phone, hubungan_pendaftar, is_form)
                VALUES (".$row['star_name'].", ".$row['profil_singkat'].", ".$type_id.", 0, ".$row['jumlah_personil'].", ".$row['star_address'].",
                ".$row['link_sosmed'].", ".$row['link_karya'].", ".$row['label'].", ".$row['ktp'].", ".$row['genre'].", ".$phone.", ".$row['jabatan'].", 1 )
            ";
            $res_query_insert_contestan = pg_query($conn2, $query_insert_contestan);

            if ($res_query_insert_contestan) {
                $contestan_id = pg_last_oid($res_query_insert_contestan);

                //INSERT IMAGE
                $url1 = unserialize($row['foto_penampil']);
                $foto_penampil = $url1['file']['file_url'];
                $url2 = unserialize($row['logo_penampil']);
                $logo_penampil = $url2['file']['file_url'];

                $query_insert_foto = "INSERT INTO galleries (image, type, parent_id) VALUES
                    (".$foto_penampil.", contestan, ".$contestan_id.")
                ";
                pg_query($conn2, $query_insert_foto);

                $query_insert_foto = "INSERT INTO galleries (image, type, parent_id) VALUES
                    (".$logo_penampil.", cover_contestan, ".$contestan_id.")
                ";
                pg_query($conn2, $query_insert_foto);

                //INSERT CONTESTAN SHOWCASE
                $explodelink = explode(',', $row['link_karya']);
                if (!empty($explodeLink[0])) {
                    $link = $explodeLink[0];
                }else {
                    $link = $row['link_karya'];
                }

                $type_karya = 'video';
                if (str_contains($link, 'youtube.com')) {
                    $explodeLink = explode('v=', $link);
                    if (!empty($explodeLink[1])) {
                        $explodeLink2 = explode('&', $explodeLink[1]);
                        $link = $explodeLink2[0];
                    }
                }elseif (str_contains($link, 'youtu.be')) {
                    $explodeLink = explode('youtu.be/', $link);
                    if (!empty($explodeLink[1])) {
                        $link = $explodeLink[1];
                    }
                }elseif (str_contains($link, '.spotify')) {
                    $type_karya = 'music';
                }elseif (str_contains($link, '.mp3')) {
                    $type_karya = 'music';
                }

                $query_insert_contestanshowcase = "INSERT INTO contestan_show_cases (contestan_id, user_apps_id, title, singer, writen, produce, type, status) VALUES
                    (".$contestan_id.", ".$user_apps_id.", ".$row['star_name'].", ".$row['star_name'].", ".$row['star_name'].", ".$row['label'].", ".$type_karya.", 1)
                ";

                $res_contestan_show_case = pg_query($conn2, $query_insert_contestanshowcase);

                if ($res_contestan_show_case) {
                    $contestan_show_case_id = pg_last_oid($res_contestan_show_case);

                    $query_insert_contestanshowcasedata = "INSERT INTO contestan_show_case_data (contestan_show_case_id, user_apps_id, file) VALUES
                        (".$contestan_show_case_id.", ".$user_apps_id.", ".$link.")
                    ";
                    pg_query($query_insert_contestanshowcasedata);
                }

            }

        }
    }


?>
