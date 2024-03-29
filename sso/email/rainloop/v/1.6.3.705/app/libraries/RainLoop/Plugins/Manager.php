<?php

namespace RainLoop\Plugins;

class Manager
{
	/**
	 * @var \RainLoop\Actions
	 */
	private $oActions;

	/**
	 * @var array
	 */
	private $aHooks;

	/**
	 * @var array
	 */
	private $aJs;

	/**
	 * @var array
	 */
	private $aAdminJs;

	/**
	 * @var array
	 */
	private $aTemplates;

	/**
	 * @var array
	 */
	private $aAdminTemplates;

	/**
	 * @var array
	 */
	private $aProcessTemplate;

	/**
	 * @var array
	 */
	private $aAdditionalParts;

	/**
	 * @var array
	 */
	private $aAdditionalAjax;

	/**
	 * @var array
	 */
	private $aPlugins;

	/**
	 * @var bool
	 */
	private $bIsEnabled;

	/**
	 * @var \MailSo\Log\Logger
	 */
	private $oLogger;

	/**
	 * @param \RainLoop\Actions $oActions
	 */
	public function __construct(\RainLoop\Actions $oActions)
	{
		$this->oLogger = null;
		$this->oActions = $oActions;
		$this->aPlugins = array();

		$this->aHooks = array();
		$this->aJs = array();
		$this->aAdminJs = array();
		$this->aTemplates = array();
		$this->aAdminTemplates = array();

		$this->aAjaxFilters = array();
		$this->aAdditionalAjax = array();
		$this->aProcessTemplate = array();

		$this->bIsEnabled = (bool) $this->oActions->Config()->Get('plugins', 'enable', false);
		if ($this->bIsEnabled)
		{
			$sList = \strtolower($this->oActions->Config()->Get('plugins', 'enabled_list', ''));
			if (0 < \strlen($sList))
			{
				$aList = \explode(',', $sList);
				$aList = \array_map('trim', $aList);

				foreach ($aList as $sName)
				{
					if (0 < \strlen($sName))
					{
						$oPlugin = $this->CreatePluginByName($sName);
						if ($oPlugin)
						{
							$oPlugin->PreInit();
							$oPlugin->Init();

							$this->aPlugins[] = $oPlugin;
						}
					}
				}
			}
		}
	}
	
	/**
	 * @param string $sName
	 * 
	 * @return \RainLoop\Plugins\AbstractPlugin | null
	 */
	public function CreatePluginByName($sName)
	{
		$oPlugin = null;
		if (\preg_match('/^[a-z0-9\-]+$/', $sName) &&
			\file_exists(APP_PLUGINS_PATH.$sName.'/index.php'))
		{
			$sClassName = $this->convertPluginFolderNameToClassName($sName);
			
			if (!\class_exists($sClassName))
			{
				include APP_PLUGINS_PATH.$sName.'/index.php';
			}

			if (\class_exists($sClassName))
			{
				$oPlugin = new $sClassName();
				if ($oPlugin instanceof \RainLoop\Plugins\AbstractPlugin)
				{
					$oPlugin
						->SetValues(APP_PLUGINS_PATH.$sName, $sName,
							\file_exists(APP_PLUGINS_PATH.$sName.'/VERSION') ?
								\file_get_contents(APP_PLUGINS_PATH.$sName.'/VERSION') : '')
						->SetPluginManager($this)
						->SetPluginConfig(new \RainLoop\Config\Plugin($sName, $oPlugin->ConfigMap()))
					;
				}
				else
				{
					$oPlugin = null;
				}
			}
		}
		
		return $oPlugin;
	}
	
	/**
	 * @return array
	 */
	public function InstalledPlugins()
	{
		$aList = array();

		$aGlob = @\glob(APP_PLUGINS_PATH.'*', GLOB_ONLYDIR|GLOB_NOSORT);
		if (\is_array($aGlob))
		{
			foreach ($aGlob as $sPathName)
			{
				$sName = \basename($sPathName);
				if (\preg_match('/^[a-z0-9\-]+$/', $sName) &&
					\file_exists($sPathName.'/index.php'))
				{
					$sVersion = @\file_get_contents($sPathName.'/VERSION');
					$aList[] = array(
						$sName, $sVersion
					);
				}
			}
		}
		else
		{
			$this->Actions()->Logger()->Write('Cannot get installed plugins from '.APP_PLUGINS_PATH,
				\MailSo\Log\Enumerations\Type::ERROR);
		}
		
		return $aList;
	}

	/**
	 * @param string $sFolderName
	 *
	 * @return string
	 */
	private function convertPluginFolderNameToClassName($sFolderName)
	{
		$aParts = array_map('ucfirst', array_map('strtolower',
			explode(' ', preg_replace('/[^a-z0-9]+/', ' ', $sFolderName))));

		return implode($aParts).'Plugin';
	}

	/**
	 * @return \RainLoop\Actions
	 */
	public function Actions()
	{
		return $this->oActions;
	}

	/**
	 * @return \RainLoop\Actions
	 */
	public function Hash()
	{
		$sResult = \md5(APP_VERSION);
		foreach ($this->aPlugins as $oPlugin)
		{
			$sResult = \md5($sResult.$oPlugin->Path().$oPlugin->Hash());
		}

		return $sResult;
	}

	/**
	 * @param bool $bAdminScope = false
	 *
	 * @return string
	 */
	public function CompileJs($bAdminScope = false)
	{
		$aResult = array();
		if ($this->bIsEnabled)
		{
			$aJs = $bAdminScope ? $this->aAdminJs : $this->aJs;
			foreach ($aJs as $sFile)
			{
				if (file_exists($sFile))
				{
					$aResult[] = file_get_contents($sFile);
				}
			}
		}

		return implode("\n", $aResult);
	}

	/**
	 * @todo
	 * @param bool $bAdminScope = false
	 *
	 * @return string
	 */
	public function CompileCss($bAdminScope = false)
	{
		return '';
	}

	/**
	 * @param bool $bAdminScope = false
	 * @return string
	 */
	public function CompileTemplate($bAdminScope = false)
	{
		$sResult = '';
		if ($this->bIsEnabled)
		{
			$aTemplates = $bAdminScope ? $this->aAdminTemplates : $this->aTemplates;
			foreach ($aTemplates as $sFile)
			{
				if (file_exists($sFile))
				{
					$sTemplateName = substr(basename($sFile), 0, -5);
					$sResult .= '<script id="'.preg_replace('/[^a-zA-Z0-9]/', '', $sTemplateName).'" type="text/html">'.
						$this->Actions()->ProcessTemplate($sTemplateName, file_get_contents($sFile)).'</script>';
				}
			}
		}

		return $sResult;
	}
	
	/**
	 * @param bool $bAdmin
	 * @param array $aAppData
	 * 
	 * @return \RainLoop\Plugins\Manager
	 */
	public function InitAppData($bAdmin, &$aAppData)
	{
		if ($this->bIsEnabled && isset($aAppData['Plugins']) && is_array($aAppData['Plugins']))
		{
			$bAuth = isset($aAppData['Auth']) && !!$aAppData['Auth'];
			foreach ($this->aPlugins as $oPlugin)
			{
				if ($oPlugin)
				{
					$aConfig = array();
					$aMap = $oPlugin->ConfigMap();
					if (\is_array($aMap))
					{
						foreach ($aMap as /* @var $oPluginProperty \RainLoop\Plugins\Property */$oPluginProperty)
						{
							if ($oPluginProperty && $oPluginProperty->AllowedInJs())
							{
								$aConfig[$oPluginProperty->Name()] =
									$oPlugin->Config()->Get('plugin', 
										$oPluginProperty->Name(),
										$oPluginProperty->DefaultValue());
							}
						}
					}

					$oPlugin->FilterAppDataPluginSection($bAdmin, $bAuth, $aConfig);

					if (0 < \count($aConfig))
					{
						$aAppData['Plugins'][$oPlugin->Name()] = $aConfig;
					}
				}
			}

			$this->RunHook('filter.app-data', array($bAdmin, &$aAppData));
		}
		
		return $this;
	}

	/**
	 * @param string $sHookName
	 * @param mixed $mCallbak
	 *
	 * @return \RainLoop\Plugins\Manager
	 */
	public function AddHook($sHookName, $mCallbak)
	{
		if ($this->bIsEnabled && is_callable($mCallbak))
		{
			if (!isset($this->aHooks[$sHookName]))
			{
				$this->aHooks[$sHookName] = array();
			}

			$this->aHooks[$sHookName][] = $mCallbak;
		}

		return $this;
	}

	/**
	 * @param string $sHookName
	 * @param bool $bAdminScope = false
	 *
	 * @return \RainLoop\Plugins\Manager
	 */
	public function AddJs($sFile, $bAdminScope = false)
	{
		if ($this->bIsEnabled)
		{
			if ($bAdminScope)
			{
				$this->aAdminJs[$sFile] = $sFile;
			}
			else
			{
				$this->aJs[$sFile] = $sFile;
			}
		}

		return $this;
	}

	/**
	 * @param string $sHookName
	 * @param bool $bAdminScope = false
	 *
	 * @return \RainLoop\Plugins\Manager
	 */
	public function AddTemplate($sFile, $bAdminScope = false)
	{
		if ($this->bIsEnabled)
		{
			if ($bAdminScope)
			{
				$this->aAdminTemplates[$sFile] = $sFile;
			}
			else
			{
				$this->aTemplates[$sFile] = $sFile;
			}
		}

		return $this;
	}

	/**
	 * @param string $sHookName
	 * @param array $aArg = array()
	 * @param bool $bLogHook = true
	 *
	 * @return \RainLoop\Plugins\Manager
	 */
	public function RunHook($sHookName, $aArg = array(), $bLogHook = true)
	{
		if ($this->bIsEnabled)
		{
			if (isset($this->aHooks[$sHookName]))
			{
				if ($bLogHook)
				{
					$this->WriteLog('Hook: '.$sHookName, \MailSo\Log\Enumerations\Type::NOTE);
				}

				foreach ($this->aHooks[$sHookName] as $mCallbak)
				{
					call_user_func_array($mCallbak, $aArg);
				}
			}
		}

		return $this;
	}

	/**
	 * @param string $sActionName
	 * @param mixed $mCallbak
	 *
	 * @return \RainLoop\Plugins\Manager
	 */
	public function AddAdditionalPartAction($sActionName, $mCallbak)
	{
		if ($this->bIsEnabled && is_callable($mCallbak))
		{
			$sActionName = strtolower($sActionName);
			if (!isset($this->aAdditionalParts[$sActionName]))
			{
				$this->aAdditionalParts[$sActionName] = array();
			}

			$this->aAdditionalParts[$sActionName][] = $mCallbak;
		}

		return $this;
	}

	/**
	 * @param string $sActionName
	 * @param array $aParts = array()
	 *
	 * @return \RainLoop\Plugins\Manager
	 */
	public function RunAdditionalPart($sActionName, $aParts = array())
	{
		$bResult = false;
		if ($this->bIsEnabled)
		{
			$sActionName = strtolower($sActionName);
			if (isset($this->aAdditionalParts[$sActionName]))
			{
				foreach ($this->aAdditionalParts[$sActionName] as $mCallbak)
				{
					$bCallResult = call_user_func_array($mCallbak, $aParts);
					if ($bCallResult && !$bResult)
					{
						$bResult = true;
					}
				}
			}
		}

		return $bResult;
	}

	/**
	 * @param string $sName
	 * @param string $sPlace
	 * @param string $sHtml
	 * @param bool $bPrepend = false
	 *
	 * @return \RainLoop\Plugins\Manager
	 */
	public function AddProcessTemplateAction($sName, $sPlace, $sHtml, $bPrepend = false)
	{
		if ($this->bIsEnabled)
		{
			if (!isset($this->aProcessTemplate[$sName]))
			{
				$this->aProcessTemplate[$sName] = array();
			}

			if (!isset($this->aProcessTemplate[$sName][$sPlace]))
			{
				$this->aProcessTemplate[$sName][$sPlace] = array();
			}

			if ($bPrepend)
			{
				array_unshift($this->aProcessTemplate[$sName][$sPlace], $sHtml);
			}
			else
			{
				array_push($this->aProcessTemplate[$sName][$sPlace], $sHtml);
			}
		}

		return $this;
	}

	/**
	 * @param string $sActionName
	 * @param mixed $mCallbak
	 *
	 * @return \RainLoop\Plugins\Manager
	 */
	public function AddAdditionalAjaxAction($sActionName, $mCallbak)
	{
		if ($this->bIsEnabled && is_callable($mCallbak) && 0 < strlen($sActionName))
		{
			$sActionName = 'Do'.$sActionName;

			if (!isset($this->aAdditionalAjax[$sActionName]))
			{
				$this->aAdditionalAjax[$sActionName] = $mCallbak;
			}
		}

		return $this;
	}

	/**
	 * @param string $sActionName
	 *
	 * @return bool
	 */
	public function HasAdditionalAjax($sActionName)
	{
		return $this->bIsEnabled && isset($this->aAdditionalAjax[$sActionName]);
	}

	/**
	 * @param string $sActionName
	 *
	 * @return mixed
	 */
	public function RunAdditionalAjax($sActionName)
	{
		if ($this->bIsEnabled)
		{
			if (isset($this->aAdditionalAjax[$sActionName]))
			{
				return call_user_func($this->aAdditionalAjax[$sActionName]);
			}
		}

		return false;
	}

	/**
	 * @param string $sLang
	 * @param array $sActionName
	 *
	 * @return \RainLoop\Plugins\Manager
	 */
	public function ReadLang($sLang, &$aLang)
	{
		if ($this->bIsEnabled)
		{
			foreach ($this->aPlugins as $oPlugin)
			{
				if ($oPlugin->UseLangs())
				{
					$sPath = $oPlugin->Path();

					\RainLoop\Utils::ReadAndAddLang($sPath.'/langs/en.ini', $aLang);
					if ('en' !== $sLang)
					{
						\RainLoop\Utils::ReadAndAddLang($sPath.'/langs/'.$sLang.'.ini', $aLang);
					}
				}
			}
		}

		return $this;
	}

	/**
	 * @param string $sName
	 * @param string $sHtml
	 *
	 * @return string
	 */
	public function ProcessTemplate($sName, $sHtml)
	{
		if (isset($this->aProcessTemplate[$sName]))
		{
			foreach ($this->aProcessTemplate[$sName] as $sPlace => $aAddHtml)
			{
				if (is_array($aAddHtml) && 0 < count($aAddHtml))
				{
					foreach ($aAddHtml as $sAddHtml)
					{
						$sHtml = str_replace('{{INCLUDE/'.$sPlace.'/PLACE}}', $sAddHtml.'{{INCLUDE/'.$sPlace.'/PLACE}}', $sHtml);
					}
				}
			}
		}

		return $sHtml;
	}

	/**
	 * @return bool
	 */
	public function bIsEnabled()
	{
		return $this->bIsEnabled;
	}
	
	/**
	 * @return int
	 */
	public function Count()
	{
		return $this->bIsEnabled ? \count($this->aPlugins) : 0;
	}

	/**
	 * @param \MailSo\Log\Logger $oLogger
	 *
	 * @return \RainLoop\Plugins\Manager
	 *
	 * @throws \MailSo\Base\Exceptions\InvalidArgumentException
	 */
	public function SetLogger($oLogger)
	{
		if (!($oLogger instanceof \MailSo\Log\Logger))
		{
			throw new \MailSo\Base\Exceptions\InvalidArgumentException();
		}

		$this->oLogger = $oLogger;

		return $this;
	}

	/**
	 * @param string $sDesc
	 * @param int $iDescType = \MailSo\Log\Enumerations\Type::INFO
	 *
	 * @return void
	 */
	public function WriteLog($sDesc, $iDescType = \MailSo\Log\Enumerations\Type::INFO)
	{
		if ($this->oLogger)
		{
			$this->oLogger->Write($sDesc, $iDescType, 'PLUGIN');
		}
	}

	/**
	 * @param string $sDesc
	 * @param int $iDescType = \MailSo\Log\Enumerations\Type::INFO
	 *
	 * @return void
	 */
	public function WriteException($sDesc, $iDescType = \MailSo\Log\Enumerations\Type::INFO)
	{
		if ($this->oLogger)
		{
			$this->oLogger->WriteException($sDesc, $iDescType, 'PLUGIN');
		}
	}
}
