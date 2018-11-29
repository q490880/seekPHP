<html>
    <head>
        <title>个人主页</title>
    </head>
    <body>
        <table>
            <?foreach ($users as $user):?>
                <tr>
                    <td>姓名:</td>
                    <td><?php echo $user['name']?></td>
                </tr>
                <tr>
                    <td>年龄:</td>
                    <td><?php echo $user['age']?></td>
                </tr>
                <tr>
                    <td>个人简介:</td>
                    <td><?php echo htmlentities($user['description'])?></td>
                </tr>
            <?endforeach;?>
        </table>
    </body>
</html>