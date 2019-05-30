<?php
    // 找到要删除的数据
    //从原来的数据中移除
    //保存删除指定数据后的数据
    //跳转回原来的列表页


    // 验证是否接收到id
    if(empty($_GET['id'])){
        // 警报
        exit('<h1>必须指定参数<?h1>');
    }

    // 接收要删除的id;
    $id = $_GET['id'];

    // 接收json文件并解析
    $data = json_decode(file_get_contents('storage.json'), true);
    // 遍历json数据
    foreach($data as $time ){
        if($time['id'] != $id) continue; //不满足条件继续循环

        // 找到要被删除的下标位置
        $index = array_search($time,$data);
        //移除从json中找到的数据
        array_splice($data,$index,1);

        // 编译新数据
        $json = json_encode($data);
        // 把新数据加到原来的数据中
        file_put_contents('storage.json',$json);

        // 跳转回列表页
        header('Location: list.php');
    }
    