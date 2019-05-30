<?php
//3dN5i,+8im.j
// 获取json数据
$contents = file_get_contents('storage.json');
//解析获取到的json数据
$data = json_decode($contents,true);
//var_dump($data);

// 判断用户不小心删除了数据报错
// if(!$data){
//     //json不存在
//     exit('数据文件异常');
// }

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>音乐列表</title>
  <link rel="stylesheet" href="bootstrap.css">
</head>
<body>
  <div class="container py-5">
    <h1 class="display-4">音乐列表</h1>
    <hr>
    <div class="mb-3">
      <a href="add.php" class="btn btn-secondary btn-sm">添加</a>
    </div>
    <table class="table table-bordered table-striped table-hover">
      <thead class="thead-dark">
        <tr>
          <th class="text-center">标题</th>
          <th class="text-center">歌手</th>
          <th class="text-center">海报</th>
          <th class="text-center">音乐</th>
          <th class="text-center">操作</th>
        </tr>
      </thead>
      <tbody class="text-center">
        <?php foreach($data as $time): ?>
            <tr>
                <td class="align-middle"><?php echo $time['title'] ?></td>
                <td class="align-middle"><?php echo $time['artist'] ?></td>
                <td class="align-middle">
                    <?php foreach($time['images'] as $src): ?>
                        <img src="<?php echo $src; ?>" alt="图片" width="100" height="100">
                    <?php endforeach ?>
                </td>
                <td class="align-middle"><audio src="<?php echo $time['source'] ?>" controls></audio></td>
                <td class="align-middle"><a class="btn btn-danger btn-sm" href="delete.php?id=<?php echo $time['id']; ?>">删除</a></td>
            </tr>
        <?php endforeach ?>
      </tbody>
    </table>
  </div>
</body>
</html>
