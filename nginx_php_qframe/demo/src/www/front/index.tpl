<html>
    <head>
        <meta http-equiv="content-Type" content="text/html; charset=utf-8" />
    </head>
    <body>
    {%*
    3. 基本语法
        0. 概述
            虽然Smarty可以处理很复杂的表达式和语法，但是最好的方式还是 保持模板语法的简洁，模板层专注于显示。
        1. 变量
            1. 普通的smarty变量 {%$variable_name%}
            2. 数组   {%$arr[0]%} {%$arr[$key]%}
            3. 对象的属性key   {%$obj->key%}
            4. 对象的函数   {%$obj->func()%}
            5. smarty配置 {%$smarty.config.foo%}
        2. 数学运算
            1. {%$x+$y%}  // 显示x加y的和
        3. 函数
            每个Smarty的标签都可以是显示一个 变量或者调用 某种类型的函数。 调用和显示的方式是在定界符内包含了函数，和其 属性， 如：{%funcname attr1="val1" attr2="val2"%}.
        4. 双引号中嵌入变量
            1. "$key 输出key变量的内容"
            2. "`$key.name` 对于数组、对象则需要使用单引号"
        5. 修改分隔符
        6. 变量的作用范围
           你可以设置Smarty对象、通过createData()建立的对象、和createTemplate()建立的对象的作用范围。 这些对象可以连接使用。 模板内可以使用全部由对象的变量和它们链条上的父对象的变量。
        7. smarty的保留变量
            可以通过PHP的保留变量 {$smarty}来访问一些环境变量
            {%smarty.server.SERVER_NAME%} 访问$_SERVER['SERVER_NAME']
            {%smarty.now%} 当前时间戳
            {%smarty.const.CONST_VAR%} 访问PHP中的常量
        8. 变量修饰器
        变量修饰器可以用于变量, 自定义函数或者字符串。 使用修饰器，需要在变量的后面加上|（竖线）并且跟着修饰器名称。 修饰器可能还会有附加的参数以便达到效果。 参数会跟着修饰器名称，用:（冒号）分开。 同时，默认全部PHP函数都可以作为修饰器来使用 (不止下面的)，而且修饰器可以被 联合使用。
        默认全部的PHP函数都可以当做修饰器
            1. cat 连接多个变量
            2. 你可以联合使用多个修饰器。 它们会按复合的顺序来作用于变量，从左到右。 它们必须以| (竖线)进行分隔。

        9. 运算符号
            1. 每个{if}必须有一个配对的{/if}. 也可以使用{else} 和 {elseif}. 全部的PHP条件表达式和函数都可以在if内使用，如||, or, &&, and, is_array(), .
        13. Smarty 成员变量
            1. template_dir 默认模板目录的设置 smarty会查询该目录寻找模板文件和执行PHP脚本。$template_dir同样可以是一个多目录值的数组，Smarty将逐个查询这些目录直到匹配的模板被找到为止。
            2. config_dir 设置存储 配置文件的目录。 默认是./configs，意味着Smarty将查询configs/并读取配置。
            3. compile_dir 保存模板编译文件的目录名称。Smarty将进入templates_c/目录来执行已编译的PHP文件。 该目录必须可写入。

        14. Smarty成员方法
            1. assign 赋值 http://www.smarty.net/docs/zh_CN/api.assign.tpl

    *%}
     <b>1. 变量</b> </br>
        你好，{%$name%}
        性别，{%$sex%}
        时间，{%$time%} </br>
        {%foreach $array as $key => $value%}
            {%$key%} {%$value%} </br>
        {%/foreach%}
        {%$carr[0]%}</br>
        {%$carr[1]%}</br>
        {%$carr[2]%}</br>
        {%$smarty.server.SERVER_NAME%} </br>
     <b>2. 数学运算</b> </br>
        {%foreach $carr as $key => $value%}
            {%$key + 1%}: {%$value%} </br>
        {%/foreach%}
     <b>8. 变量修饰器</b> </br>
     大写名字 {%$name|upper%} </br>
     格式化当前时间 {%$smarty.now|date_format:"%Y/%m/%d"%} </br>
     使用PHP函数 {%"="|str_repeat:10%}
     <b>8.1 连接多个变量</b></br>
     后缀 yesterday {%$name|cat:' yesterday.'%}
     <b>8.2 复合修饰器</b></br>
     复合后缀 yesterday {%$name|upper|cat:' yesterday.'|upper%}</br>
     复合后缀 yesterday {%$name|upper|cat:' yesterday.'%}</br>
     
     <b> 9. 运算符号
     {%if $name == '明天' %}
       name is equal to 明天
     {%else%}
       name is NOT equal to 明天
     {%/if%}

     




    </body>
</html>
