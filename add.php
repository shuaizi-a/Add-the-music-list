<?php

function  add_music(){

  $data = array(); //准备一个空容器，用来装最终要保存的数据好追加到json数据中
  $data['id'] = uniqid();//随机生成id
  // 1.校验文件

    // 1.1检验填写信息
    if(empty($_POST['title']) ){ //判断输入文件标题没
        //提示信息
        $GLOBALS['jingao'] = '请输入标题'; 
        return;
    }
    
    if(empty($_POST['artist']) ){ //判断输入歌手名没
      //提示信息
      $GLOBALS['jingao'] = '请输入歌手名'; 
      return;
    }

    //记下从2title和artist得到数据保存到data中
    $data['title'] = $_POST['title'];
    $data['artist'] = $_POST['artist'];

    //================ 1.2检验音乐上传文件===================

    if( empty($_FILES['source'])){//判断提交的表单里面没有source文件域 
      $GLOBALS['jingao'] = '请正确提交文件'; 
      return;
    }
    $source = $_FILES['source'];//判断用户是否选择了文件
    if( $source['error'] != UPLOAD_ERR_OK){
      $GLOBALS['jingao'] = '选择音乐文件'; 
      return;
    }

    // 校验文件大小
    if($source['size'] > 10 * 1024 * 1024){
      $GLOBALS['jingao'] = '音乐文件过大'; 
      return;
    }
    if($source['size'] < 1 * 1024 * 1024){
      $GLOBALS['jingao'] = '音乐文件过小'; 
      return; 
    }
    // 校验文件类型
    $allowed_types = array('audio/mp3', 'audio/wma');
    if(!in_array($source['type'],$allowed_types)){
      $GLOBALS['jingao'] = '音乐上传文件类型错误 '; 
      return;
    }

    //音乐上传成功，但是还在临时目录中
    $temporary = $source['tmp_name'];//临时存放目录
    $target = 'uploads/audio/'.uniqid().'-'.$source['name'];//新目录

    // 移动文件
    $movee = move_uploaded_file($temporary,$target);

    // 判断文件移动成功没
    if(!$movee){
      $GLOBALS['jingao'] = '音乐上传失败'; 
      return;
    }
    $GLOBALS['jingao'] = '音乐上传成功'; 

    // 接收音乐地址保存到data中
    $data['source'] = $target;

    // =============1.2检验图片上传文件=======================

    if( empty($_FILES['images'])){//判断提交的表单里面没有images文件域 
      $GLOBALS['jingao'] = '请正确提交文件'; 
      return;
    }
    
    $images = $_FILES['images'];

    //准备一个容器装所有的海报路径
    $date['images'] = array();

    //  循环多文件上传失败
    for($i =0; $i<count($images['name']); $i++ ){
        if($images['error'][$i] !== UPLOAD_ERR_OK){
          $GLOBALS['jingao'] = '选择图片文件';
          return;
        }

        //校验文件大小
        if($images['size'][$i] > 10 * 1024 * 1024){
          $GLOBALS['jingao'] = '图片文件过大'; 
          return;
        }

        // 校验文件类型
        $allowed_typess = array('image/jpeg', 'image/png', 'image/gif');
        if(!in_array($images['type'][$i],$allowed_typess)){
          $GLOBALS['jingao'] = '上传图片文件类型错误 '; 
          return;
        }

        //图片上传成功，但是还在临时目录中
        $temporarytp = $images['tmp_name'][$i];//临时存放目录
        $targettp = 'uploads/images/'.uniqid().'-'.$images['name'][$i];//新目录

        // 移动文件
        $move = move_uploaded_file($temporarytp,$targettp);

        // 判断文件移动成功没
        if(!$move){
          $GLOBALS['jingao'] = '图片上传失败'; 
          return;
        }
        // $GLOBALS['jingao'] = '图片上传成功'; 

        // 接收全部的海报路径
        $data['images'][] = $targettp;
    }

    
    // 获取json数据并解析获取到的json数据
    $origin = json_decode(file_get_contents('storage.json'),true);

    // 追加新数据到json中去
    array_push($origin,$data);

    // 编码新数据
    $json = json_encode($origin);
    
    // 把新数据写入json文件中去
    file_put_contents('storage.json',$json);

    // 跳转回列表页
    header('Location: list.php');

};



//判断接收类型，往下执行代码
  if($_SERVER['REQUEST_METHOD'] === 'POST' ){
      //调用函数
      add_music();
  }

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>添加新音乐</title>
  <link rel="stylesheet" href="bootstrap.css">
</head>
<body>
  <div class="container py-5">
    <h1 class="display-4">添加新音乐</h1>
    <hr>
    <!-- 警告框 -->
    <?php if (isset($jingao)): ?>
    <div class="alert alert-danger" role="alert">
      <?php echo $jingao; ?>
    </div>
    <?php endif ?>
    <!-- 警告框结束                                                                   autocomplete="off" -->
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
      <div class="form-group">
        <label for="title">标题</label>
        <input type="text" class="form-control" id="title" name="title">
      </div>
      <div class="form-group">
        <label for="artist">歌手</label>
        <input type="text" class="form-control" id="artist" name="artist">
      </div>
      <div class="form-group">
        <label for="images">海报</label>
        <input type="file" class="form-control" id="images" name="images[]" accept="image/*" multiple>
      </div>
      <div class="form-group">
        <label for="source">音乐</label>
        <!-- accept 可以限制文件域能够选择的文件种类，值是 MIME Type -->
        <input type="file" class="form-control" id="source" name="source" accept="audio/*">
      </div>
      <button class="btn btn-primary btn-block">保存</button>
    </form>
  </div>
</body>
</html>
