TinyBS
============

Howto
------------

    cd /path/to/project && php composer.phar slef-update && php composer.phar install
        详细见composer怎么用

为什么
------------

	Zendframework2的代码设计非常优雅，无奈这个框架也很很臃肿的，写这个skeleton仅仅为了“瘦瘦”的使用它而已。
	使用Zend/Mvc中的route部分作Request路由匹配，这部分很好用。不再使用EventModule模块作为默认的启动策略。
	(Zend\Mvc模块主要就是通过EventManager来触发几个核心事件来完成启动流程的)所以还是改用简单的MVC吧。
	鄙人觉得php还是简单好，MVVM的设计固然先进，但对于php这类脚本语言，使用这些优秀而复杂的设计会浪费很多加载时间。
	(有时间可以看看Zendframework2的MVVM实现和EventManager，阅读源代码能让你更好地理解这些高阶的设计模式)
	我还是觉得简单MVC比较合适。不在使用ModuleManager，和EventManager一样，留作普通模块，按需调用。
	目前在研究怎么使用ServiceManager比较好，ServiceManager作为Di的上层套件，实现解耦的利器。
	因此ServiceManager成了我跟框架唯一的耦合的地方, 即使改用别的框架的来bootstrap项目的话，
	成本也就在于按ZendFramework的ServiceManager的风格来封装一个简单的、合适的、依赖管理类而已。

模塊加載
------------

    約定$module_path爲模塊根目錄。
    $module_path/config/module.config.php將以模塊配置文件的形式被加載。
    $module_path/src則被加入composer autoloader使得對應的模塊能被加載。

config/config(\*).php下的配置文件和src/main/conf/\*/module.config.php的作用
------------

    1. config.lib.module.php中放置每次都为加载的用户库模块，对应的模块文件放在src/main/php
    若加載的是個數組，那就按數組提供的值加載，規則爲 
        a).指定module_name和module_path兩個鍵值，那麼以array['module_name']爲模塊名，array['module_path']爲模塊根目錄，
        b).無鍵值數組，則以array[0]爲模塊名，array[1]爲模塊根目錄，
    
    2. src/main/conf/\*/module.config.php中放置应用模块，对应的模块文件放在src/main/php
    \*匹配任意模塊名，但需要和src/main/php下的對應的模塊名同名
    
    3. config.{psr0,psr4,classmap}.php 则用来配置composer的加载行为的，
    可以看看composer autoloader怎么玩（不加載src/main/conf下的這幾個文件）
    
    4. src/main/conf/*/module.config.php这里本是放模块的配置文件的，
    config/config.lib.module.php中指定的模块全部都会被加载，其内容能在ComposerAutoloader中能获取。(粗糙的设计，待改进)
    

TODO
------------

    1. 没有渲染策略。。（暫時打算只提供api服務，至於渲染打算完後看時間是否允許）
    
    2. ServiceManager在ZendFramework2 Skeleton中放置了多延迟加载的类，我只留下了3个，放在tinybs/config/config.servicemanager.factory.php,这3个是用于做RouteMatch用的。其他的都改为按需加载。没测试有什么问题。
    
    3. 改天把官网上的Ablum模块拿下来玩玩
    
Compare
------------

    1. 原來的module.config.php是用來給zf2框架加載的提供配置的，TinyBs只適用service_manager和route這兩項配置
    
    2. 不再使用Module.php，原來有關的ServiceManager的配置可能要移動到module.config.php中去
    
    3. 沒做模板渲染，不適用有模板的Module