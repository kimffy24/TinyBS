<?php

namespace TinyBS\SimpleMvc\Utils;

/**
 * 若果想仅仅进行某些模块初始化工作，请扩展此接口
 * 若果想模块初始化时使用ServiceManager，
 * 请继承AbstractModuleInitialization抽象类，
 * 框架会为其注入ServiceManager
 * 
 * @author Jiefzz
 *
 */
interface ModuleInitializationInterface {
	public function initModule();
}