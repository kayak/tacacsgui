<?php

namespace tgui\Controllers\TACConfig;

use tgui\Models\TACUsers;
use tgui\Models\TACUserGrps;
use tgui\Models\TACDevices;
use tgui\Models\TACDeviceGrps;
use tgui\Models\TACGlobalConf;
use tgui\Models\TACServices;
use tgui\Models\TACCMD;
use tgui\Models\TACACL;
use tgui\Models\MAVISOTP;
use tgui\Models\MAVISSMS;
use tgui\Models\MAVISLDAP;
use tgui\Models\MAVISLocal;

/**
 *
 */
class ConfigPatterns
{
  public static $ciscoWLCRoles =
  [
    0 => 'ALL',
    2 => 'LOBBY',
    4 => 'MONITOR',
    8 => 'WLAN',
    10 => 'CONTROLLER',
    20 => 'WIRELESS',
    40 => 'SECURITY',
    80 => 'MANAGEMENT',
    100 => 'COMMANDS',
  ];

  public static $crypto_flag = array(0 => 'clear', 1 => 'crypt', 2 => 'crypt', 3 => 'mavis', 4 => 'login');
	public static $html_tags = array(
		'comment' => [
			0 => '<tac_comment>',
			1 => '</tac_comment>'
		],
		'attr' => [
			0 => '<tac_attr>',
			1 => '</tac_attr>'
		],
		'param' => [
			0 => '<tac_param>',
			1 => '</tac_param>'
		],
		'val' => [
			0 => '<tac_val>',
			1 => '</tac_val>'
		],
		'object' => [
			0 => '<tac_object>',
			1 => '</tac_object>'
		],
	);

  public static function tacSpawndPartGen($html)
	{
		$html = (empty($html)) ? false : true;

		$globalVariables=TACGlobalConf::select('port')->first();
		$outputSpawnd = array();
		$outputSpawnd[0][0]=array('title_flag' => 1, 'name' => (
		($html) ? self::$html_tags['comment'][0] . "####SPAWND####" . self::$html_tags['comment'][1]
		:
		"####SPAWND####" ));
			///EMPTY ARRAY///
			$outputSpawnd[1] = array();
			///GENERAL CONF TITLE///
			$outputSpawnd[1][0] = array('title_flag' => 0, 'name' =>"");
			///LISTENING PORT///
			array_push($outputSpawnd[1], (
				($html) ? self::$html_tags['attr'][0] . 'listen' . self::$html_tags['attr'][1] .
				' = { '.self::$html_tags['param'][0].'port'.self::$html_tags['param'][1].
				' = '.self::$html_tags['val'][0].$globalVariables['port'].self::$html_tags['val'][1].' }'
				:
				'listen = { port = '.$globalVariables['port'].' }'));


		return $outputSpawnd;
	}

  public static function tacGeneralPartGen($html)
	{
		$html = (empty($html)) ? false : true;

		$globalVariables=TACGlobalConf::select()->first();
		$outputGeneralConf = array();
		$outputGeneralConf[0][0]=array('title_flag' => 1, 'name' => (
			($html) ? self::$html_tags['comment'][0] . "####GENERAL CONFIGURATION####" . self::$html_tags['comment'][1]
			:
			"####GENERAL CONFIGURATION####"));
			///EMPTY ARRAY///
			$outputGeneralConf[1] = array();
			///GENERAL CONF TITLE///
			$outputGeneralConf[1][0] = array('title_flag' => 0, 'name' =>"");
			///////////MANUAL CONFIGURATION/////////////
			if ($globalVariables['manual']!="")
			{
				array_push($outputGeneralConf[1], ($html) ? self::$html_tags['comment'][0] . '###MANUAL CONFIGURATION START###' . self::$html_tags['comment'][1]
				:
				'###MANUAL CONFIGURATION START###');
				$arrayManual=explode(PHP_EOL, $globalVariables['manual']);
				foreach($arrayManual as $item)
				{
					array_push($outputGeneralConf[1], $item);
				}
				array_push($outputGeneralConf[1], ($html) ? self::$html_tags['comment'][0] . '###MANUAL CONFIGURATION END###' . self::$html_tags['comment'][1]
				:
				'###MANUAL CONFIGURATION END###');
			}
			///ACCOUNTING LOG///
			array_push($outputGeneralConf[1],
			($html) ? self::$html_tags['attr'][0] . 'accounting log' . self::$html_tags['attr'][1] . ' = ' .self::$html_tags['val'][0] . $globalVariables['accounting'] . self::$html_tags['val'][1]
			:
			'accounting log = '.$globalVariables['accounting']);
			///AUTHENTICATION LOG///
			array_push($outputGeneralConf[1],
			($html) ? self::$html_tags['attr'][0] . 'authentication log' . self::$html_tags['attr'][1] . ' = ' .self::$html_tags['val'][0] . $globalVariables['authentication'] . self::$html_tags['val'][1]
			:
			'authentication log = '.$globalVariables['authentication']);
			///AUTHORIZATION LOG///
			array_push($outputGeneralConf[1],
			($html) ? self::$html_tags['attr'][0] . 'authorization log' . self::$html_tags['attr'][1] . ' = ' .self::$html_tags['val'][0] . $globalVariables['authorization'] . self::$html_tags['val'][1]
			:
			'authorization log = '.$globalVariables['authorization']);
			///CONNECTION TIMEOUT TO NAS///
			array_push($outputGeneralConf[1],
			($html) ? self::$html_tags['attr'][0] . 'connection timeout' . self::$html_tags['attr'][1] . ' = ' .self::$html_tags['val'][0] . $globalVariables['connection_timeout'] . self::$html_tags['val'][1]
			:
			'connection timeout = '.$globalVariables['connection_timeout']);
			///Context TIMEOUT///
			array_push($outputGeneralConf[1],
			($html) ? self::$html_tags['attr'][0] . 'context timeout' . self::$html_tags['attr'][1] . ' = ' .self::$html_tags['val'][0] . $globalVariables['context_timeout'] . self::$html_tags['val'][1]
			:
			'context timeout = '.$globalVariables['context_timeout']);
			///Max attempt///
			array_push($outputGeneralConf[1],
			($html) ? self::$html_tags['attr'][0] . 'password max-attempts' . self::$html_tags['attr'][1] . ' = ' .self::$html_tags['val'][0] . $globalVariables['max_attempts'] . self::$html_tags['val'][1]
			:
			'password max-attempts = '.$globalVariables['max_attempts']);
			///Backoff settings///
			array_push($outputGeneralConf[1],
			($html) ? self::$html_tags['attr'][0] . 'password backoff' . self::$html_tags['attr'][1] . ' = ' .self::$html_tags['val'][0] . $globalVariables['backoff'] . self::$html_tags['val'][1]
			:
			'password backoff = '.$globalVariables['backoff']);

		return $outputGeneralConf;
	}

	public static function tacDevicesPartGen($html = false, $id = 0)
	{
    $allACL_array = TACACL::select('id','name')->where([['line_number','=',0]])->get()->toArray();
		$allUserGroups_array = TACUserGrps::select('id','name')->get()->toArray();
		$allACL = array();
		$allUserGroups = array();
		foreach($allACL_array as $acl)
		{
			$allACL[$acl['id']]=$acl['name'];
		}

		foreach($allUserGroups_array as $ugrp)
		{
			$allUserGroups[$ugrp['id']]=$ugrp['name'];
		}

		$allDevices = ( $id == 0 ) ? TACDevices::select()->get()->toArray() : TACDevices::select()->where('id', $id)->get()->toArray();
		if ( $id == 0 ) $outputDevices[0][0]=array('title_flag' => 1, 'name' =>
		($html) ? self::$html_tags['comment'][0] . "####LIST OF HOSTS####" . self::$html_tags['comment'][1]
		:
		"####LIST OF HOSTS####");
		$allGroupsArrTemp=TACDeviceGrps::select('id','name')->get()->toArray();
		$allGroupsArr=array();
		foreach($allGroupsArrTemp as $gropItem)
		{
			$allGroupsArr[$gropItem['id']]=$gropItem['name'];
		}
		foreach($allDevices as $host)
		{
			if ($host['disabled'] AND $id == 0) continue;
			///EMPTY ARRAY///
			$outputDevices[$host['id']] = array();
			///DEVICE TITLE///
			$outputDevices[$host['id']][0] = array('title_flag' => 0, 'name' =>"");
			///DEVICE NAME///
			array_push($outputDevices[$host['id']],
			($html) ? self::$html_tags['attr'][0] . "host" . self::$html_tags['attr'][1] . ' = ' . self::$html_tags['object'][0] .$host['name']. self::$html_tags['object'][1] . ' {'
			:
			'host = '.$host['name'].' {');
			///DEVICE IP ADDRESS///
			array_push($outputDevices[$host['id']],
			($html) ? '	'.self::$html_tags['param'][0] . "address" . self::$html_tags['param'][1] . ' = ' . self::$html_tags['val'][0] .$host['ipaddr'].'/'.$host['prefix']. self::$html_tags['val'][1]
			:
			' address = "'.$host['ipaddr'].'/'.$host['prefix'].'"');
			///DEVICE KEY///
			if ($host['key']!='')array_push($outputDevices[$host['id']],
			($html) ? '	'.self::$html_tags['param'][0] . "key" . self::$html_tags['param'][1] . ' = "' . self::$html_tags['val'][0] .$host['key']. self::$html_tags['val'][1].'"'
			:
			' key = "'.$host['key'].'"');
			///DEVICE ENABLE///
			if ($host['enable']!='')array_push($outputDevices[$host['id']],
			($html) ? '	'.self::$html_tags['param'][0] . "enable" . self::$html_tags['param'][1] . ' = ' . self::$html_tags['val'][0] .self::$crypto_flag[$host['enable_flag']] . ' '. $host['enable'] . self::$html_tags['val'][1]
			:
			' enable = '.self::$crypto_flag[$host['enable_flag']].' '.$host['enable']);
      ///DEVICE ACL///
			if ($host['acl'] > 0)array_push($outputDevices[$host['id']],
			($html) ? '	'.self::$html_tags['param'][0] . "access acl" . self::$html_tags['param'][1] . ' = ' . self::$html_tags['val'][0] . $allACL[$host['acl']] . self::$html_tags['val'][1]
			:
			'	access acl = '. $allACL[$host['acl']]);
      ///DEFAULT USER GROUP///
			if ($host['user_group'] > 0)array_push($outputDevices[$host['id']],
			($html) ? '	'.self::$html_tags['param'][0] . "default group" . self::$html_tags['param'][1] . ' = ' . self::$html_tags['val'][0] . $allUserGroups[$host['user_group']] . self::$html_tags['val'][1]
			:
			'	default group = '.$allUserGroups[$host['user_group']]);
      ///CONNECTION TIMEOUT///
			if ($host['connection_timeout'] > 0)array_push($outputDevices[$host['id']],
			($html) ? '	'.self::$html_tags['param'][0] . "connection timeout" . self::$html_tags['param'][1] . ' = ' . self::$html_tags['val'][0] . $host['connection_timeout'] . self::$html_tags['val'][1]
			:
			'	connection timeout = '.$host['connection_timeout']);
			///DEVICE BANNER WELCOME///
			if ($host['banner_welcome']!='')array_push($outputDevices[$host['id']],
			($html) ? ' '.self::$html_tags['param'][0] . "welcome banner" . self::$html_tags['param'][1] . ' = ' . self::$html_tags['val'][0] .'"'.$host['banner_welcome'].'"'. self::$html_tags['val'][1]
			:
			' welcome banner = "'.$host['banner_welcome'].'"');
			///DEVICE BANNER MOTD///
			if ($host['banner_motd']!='')array_push($outputDevices[$host['id']],
			($html) ? ' '.self::$html_tags['param'][0] . "motd banner" . self::$html_tags['param'][1] . ' = ' . self::$html_tags['val'][0] .'"'.$host['banner_motd'].'"'. self::$html_tags['val'][1]
			:
			' motd banner = "'.$host['banner_motd'].'"');
			///DEVICE BANNER FAILED AUTH///
			if ($host['banner_failed']!='')array_push($outputDevices[$host['id']],
			($html) ? ' '.self::$html_tags['param'][0] . "failed authentication banner" . self::$html_tags['param'][1] . ' = ' . self::$html_tags['val'][0] .'"'.$host['banner_failed'].'"'. self::$html_tags['val'][1]
			:
			' failed authentication banner = "'.$host['banner_failed'].'"');
			///DEVICE GROUP///
			array_push($outputDevices[$host['id']],
			($html) ? '	'.self::$html_tags['param'][0] . "template" . self::$html_tags['param'][1] . ' = ' . self::$html_tags['val'][0] .$allGroupsArr[$host['group']]. self::$html_tags['val'][1]
			:
			' template = '.$allGroupsArr[$host['group']].'');
			///DEVICE MANUAL CONFIGURATION///
			if ($host['manual']!="")
			{
				array_push($outputDevices[$host['id']], ($html) ? self::$html_tags['comment'][0] . '###MANUAL CONFIGURATION START###' . self::$html_tags['comment'][1]
				:
				'###MANUAL CONFIGURATION START###');
				$arrayManual=explode(PHP_EOL, $host['manual']);
				foreach($arrayManual as $item)
				{
					array_push($outputDevices[$host['id']], $item);
				}
				array_push($outputDevices[$host['id']], ($html) ? self::$html_tags['comment'][0] . '###MANUAL CONFIGURATION END###' . self::$html_tags['comment'][1]
				:
				'###MANUAL CONFIGURATION END###');
			}

			array_push($outputDevices[$host['id']],
			($html) ? '} ' . self::$html_tags['comment'][0] . '#END OF '.$host['name'] . self::$html_tags['comment'][1]
			:
			'} #END OF '.$host['name']);

		}

		return $outputDevices;
	}

	public static function tacDeviceGroupsPartGen($html = false, $id = 0)
	{
    $allACL_array = TACACL::select('id','name')->where([['line_number','=',0]])->get()->toArray();
		$allUserGroups_array = TACUserGrps::select('id','name')->get()->toArray();
		$allACL = array();
		$allUserGroups = array();
		foreach($allACL_array as $acl)
		{
			$allACL[$acl['id']]=$acl['name'];
		}

		foreach($allUserGroups_array as $ugrp)
		{
			$allUserGroups[$ugrp['id']]=$ugrp['name'];
		}

		$allDeviceGroups = ( $id == 0 ) ? TACDeviceGrps::select()->get()->toArray() : TACDeviceGrps::select()->where('id', $id)->get()->toArray();
		if ( $id == 0 ) $outputDeviceGroups[0][0]=array('title_flag' => 1, 'name' =>
		($html) ? self::$html_tags['comment'][0] . "####LIST OF DEVICE GROUPS####" . self::$html_tags['comment'][1]
		:
		"####LIST OF DEVICE GROUPS####");
		foreach($allDeviceGroups as $group)
		{
			///EMPTY ARRAY///
			$outputDeviceGroups[$group['id']] = array();
			///GROUP TITLE///
			$outputDeviceGroups[$group['id']][0] = array('title_flag' => 0, 'name' =>"");
			///GROUP NAME///
			array_push($outputDeviceGroups[$group['id']],
			($html) ? self::$html_tags['attr'][0] . "host" . self::$html_tags['attr'][1] . ' = ' . self::$html_tags['object'][0] .$group['name']. self::$html_tags['object'][1] . ' {'
			:
			'host = '.$group['name'].' {');
			///GROUP KEY///
			if ($group['key']!='')array_push($outputDeviceGroups[$group['id']],
			($html) ? '	'.self::$html_tags['param'][0] . "key" . self::$html_tags['param'][1] . ' = "' . self::$html_tags['val'][0] .$group['key']. self::$html_tags['val'][1] .'"'
			:
			' key = "'.$group['key'] .'"');
			///GROUP ENABLE///
			if ($group['enable']!='')array_push($outputDeviceGroups[$group['id']],
			($html) ? '	'.self::$html_tags['param'][0] . "enable" . self::$html_tags['param'][1] . ' = ' . self::$html_tags['val'][0] . self::$crypto_flag[$group['enable_flag']] . ' ' . $group['enable']. self::$html_tags['val'][1]
			:
			' enable = '.self::$crypto_flag[$group['enable_flag']].' '.$group['enable']);
      ///DEVICE ACL///
      if ($group['acl'] > 0)array_push($outputDeviceGroups[$group['id']],
      ($html) ? '	'.self::$html_tags['param'][0] . "access acl" . self::$html_tags['param'][1] . ' = ' . self::$html_tags['val'][0] . $allACL[$group['acl']] . self::$html_tags['val'][1]
      :
      '	access acl = '. $allACL[$group['acl']]);
      ///DEFAULT USER GROUP///
      if ($group['user_group'] > 0)array_push($outputDeviceGroups[$group['id']],
      ($html) ? '	'.self::$html_tags['param'][0] . "default group" . self::$html_tags['param'][1] . ' = ' . self::$html_tags['val'][0] . $allUserGroups[$group['user_group']] . self::$html_tags['val'][1]
      :
      '	default group = '.$allUserGroups[$group['user_group']]);
      ///CONNECTION TIMEOUT///
      if ($group['connection_timeout'] > 0)array_push($outputDeviceGroups[$group['id']],
      ($html) ? '	'.self::$html_tags['param'][0] . "connection timeout" . self::$html_tags['param'][1] . ' = ' . self::$html_tags['val'][0] . $group['connection_timeout'] . self::$html_tags['val'][1]
      :
      '	connection timeout = '.$group['connection_timeout']);
			///GROUP BANNER WELCOME///
			if ($group['banner_welcome']!='')array_push($outputDeviceGroups[$group['id']],
			($html) ? ' '.self::$html_tags['param'][0] . "welcome banner" . self::$html_tags['param'][1] . ' = ' . self::$html_tags['val'][0] .'"'.$group['banner_welcome'].'"'. self::$html_tags['val'][1]
			:
			' welcome banner = "'.$group['banner_welcome'].'"');
			///GROUP BANNER MOTD///
			if ($group['banner_motd']!='')array_push($outputDeviceGroups[$group['id']],
			($html) ? ' '.self::$html_tags['param'][0] . "motd banner" . self::$html_tags['param'][1] . ' = ' . self::$html_tags['val'][0] .'"'.$group['banner_motd'].'"'. self::$html_tags['val'][1]
			:
			' motd banner = "'.$group['banner_motd'].'"');
			///GROUP BANNER FAILED AUTH///
			if ($group['banner_failed']!='')array_push($outputDeviceGroups[$group['id']],
			($html) ? ' '.self::$html_tags['param'][0] . "failed authentication banner" . self::$html_tags['param'][1] . ' = ' . self::$html_tags['val'][0] .'"'.$group['banner_failed'].'"'. self::$html_tags['val'][1]
			:
			' failed authentication banner = "'.$group['banner_failed'].'"');
			///GROUP MANUAL CONFIGURATION///
			if ($group['manual']!="")
			{
				array_push($outputDeviceGroups[$group['id']], ($html) ? self::$html_tags['comment'][0] . '###MANUAL CONFIGURATION START###' . self::$html_tags['comment'][1]
				:
				'###MANUAL CONFIGURATION START###');
				$arrayManual=explode(PHP_EOL, $group['manual']);
				foreach($arrayManual as $item)
				{
					array_push($outputDeviceGroups[$group['id']], $item);
				}
				array_push($outputDeviceGroups[$group['id']], ($html) ? self::$html_tags['comment'][0] . '###MANUAL CONFIGURATION END###' . self::$html_tags['comment'][1]
				:
				'###MANUAL CONFIGURATION END###');
			}
			array_push($outputDeviceGroups[$group['id']],
			($html) ? '} ' . self::$html_tags['comment'][0] . '#END OF '.$group['name'] . self::$html_tags['comment'][1]
			:
			'} #END OF '.$group['name']);

		}

		return $outputDeviceGroups;
	}

	public static function tacACLPartGen($html = false, $id = 0)
	{
		$allACL = ( $id == 0 ) ? TACACL::select()->where([['line_number','=',0]])->get()->toArray() : TACACL::select()->where([['line_number','=',0],['id','=',$id]])->get()->toArray();

		if ( $id == 0 ) $outputACL[0][0]=array('title_flag' => 1, 'name' =>
		($html) ? self::$html_tags['comment'][0] . "####LIST OF ACL####" . self::$html_tags['comment'][1]
		:
		"####LIST OF ACL####");
		foreach($allACL as $acl)
		{
			///EMPTY ARRAY///
			$outputACL[$acl['id']] = array();
			///ACL TITLE///
			$outputACL[$acl['id']][0] = array('title_flag' => 0, 'name' =>"");
			array_push($outputACL[$acl['id']], ($html) ? self::$html_tags['comment'][0] . '###ACL '.$acl['name'].' START###' . self::$html_tags['comment'][1]
			:
			'###ACL '.$acl['name'].' START###');
			$allAces = TACACL::select()->where([['line_number','<>',0],['name','=',$acl['name']]])->get()->toArray();
			foreach($allAces as $ace)
			{
				///ACL NAME///
				array_push($outputACL[$acl['id']],
				($html) ? self::$html_tags['attr'][0] . "acl" . self::$html_tags['attr'][1] . ' = ' . self::$html_tags['object'][0] .$acl['name']. self::$html_tags['object'][1] . ' '.$ace['action'].' {'
				:
				'acl = '.$acl['name'].' '.$ace['action'].' {');

				///ACL NAC///
				array_push($outputACL[$acl['id']],
				($html) ? self::$html_tags['param'][0] . "	nac" . self::$html_tags['param'][1] . ' = ' . self::$html_tags['val'][0] .$ace['nac']. self::$html_tags['val'][1]
				:
				'nac = '.$ace['nac']);

				///ACL NAS///
				array_push($outputACL[$acl['id']],
				($html) ? self::$html_tags['param'][0] . "	nas" . self::$html_tags['param'][1] . ' = ' . self::$html_tags['val'][0] .$ace['nas']. self::$html_tags['val'][1]
				:
				'nas = '.$ace['nas']);
				///ACL NAS///
				array_push($outputACL[$acl['id']], '}');
			}
			array_push($outputACL[$acl['id']], ($html) ? self::$html_tags['comment'][0] . '###ACL '.$acl['name'].' END###' . self::$html_tags['comment'][1]
			:
			'###ACL '.$acl['name'].' END###');
		}

		return $outputACL;
	}

	public static function tacUserGroupsPartGen($html = false, $id = 0)
	{

		$allUserGroups = ( $id == 0 ) ? TACUserGrps::select()->get()->toArray() : TACUserGrps::select()->where('id', $id)->get()->toArray();
		$allACL_array = TACACL::select('id','name')->where([['line_number','=',0]])->get()->toArray();

		$allACL = array();
		foreach($allACL_array as $acl)
		{
			$allACL[$acl['id']]=$acl['name'];
		}

		if ( $id == 0 ) $outputUserGroup[0][0]=array('title_flag' => 1, 'name' =>
		($html) ? self::$html_tags['comment'][0] . "####LIST OF USER GROUPS####" . self::$html_tags['comment'][1]
		:
		"####LIST OF USER GROUPS####");
		foreach($allUserGroups as $group)
		{
			if ($group['priv-lvl'] < 0) $group['priv-lvl'] = 15;
			///EMPTY ARRAY///
			$outputUserGroup[$group['id']] = array();
			///USER GROUP TITLE///
			$outputUserGroup[$group['id']][0] = array('title_flag' => 0, 'name' =>"");
			///USER GROUP NAME///
			array_push($outputUserGroup[$group['id']],
			($html) ? self::$html_tags['attr'][0] . "group" . self::$html_tags['attr'][1] . ' = ' . self::$html_tags['object'][0] .$group['name']. self::$html_tags['object'][1] . ' {'
			:
			'group = '.$group['name'].' {');
			///USER GROUP ENABLE///
			if ($group['enable'] != '')array_push($outputUserGroup[$group['id']],
			($html) ? self::$html_tags['param'][0] . "enable" . self::$html_tags['param'][1] . ' = ' . self::$html_tags['val'][0] . self::$crypto_flag[$group['enable_flag']] .' '. $group['enable']. self::$html_tags['val'][1]
			:
			'enable = '.self::$crypto_flag[$group['enable_flag']].' '.$group['enable']);
			///USER GROUP MESSAGE///
			if ($group['message']!='')array_push($outputUserGroup[$group['id']],
			($html) ? self::$html_tags['param'][0] . "message" . self::$html_tags['param'][1] . ' = ' . self::$html_tags['val'][0] .'"'.$group['message'].'"'. self::$html_tags['val'][1]
			:
			'message = "'.$group['message'].'"');
      ///USER Valid From///
      if ($group['valid_from']!='')array_push($outputUserGroup[$group['id']],
			($html) ? '	'.self::$html_tags['param'][0] . "valid from" . self::$html_tags['param'][1] . ' = ' . self::$html_tags['val'][0] .strtotime($group['valid_from']). self::$html_tags['val'][1] .' # '.$group['valid_from']
			:
			'	valid from = '.strtotime($group['valid_from']).' # '.$group['valid_from']);
      ///USER Valid Until///
      if ($group['valid_until']!='')array_push($outputUserGroup[$group['id']],
			($html) ? '	'.self::$html_tags['param'][0] . "valid until" . self::$html_tags['param'][1] . ' = ' . self::$html_tags['val'][0] .strtotime($group['valid_until']). self::$html_tags['val'][1] .' # '.$group['valid_until']
			:
			'	valid until = '.strtotime($group['valid_until']).' # '.$group['valid_until']);
			///USER GROUP ACL///
			if ($group['acl'] > 0) {
				array_push($outputUserGroup[$group['id']],
				($html) ? '	' .self::$html_tags['param'][0] . "acl" . self::$html_tags['param'][1] . ' = ' . self::$html_tags['val'][0] . $allACL[$group['acl']] . self::$html_tags['val'][1]
				:
				'	acl = '. $allACL[$group['acl']]);
			}
      ///USER CLIENT IP///
      if ($group['client_ip'] > 0)array_push($outputUserGroup[$group['id']],
      ($html) ? '	'.self::$html_tags['param'][0] . "client" . self::$html_tags['param'][1] . ' = ' . self::$html_tags['val'][0] . $group['client_ip'] . self::$html_tags['val'][1] . ' # NAC ip must be in that range'
      :
      '	client = '. $group['client_ip']);
      ///USER SERVER IP///
      if ($group['server_ip'] > 0)array_push($outputUserGroup[$group['id']],
      ($html) ? '	'.self::$html_tags['param'][0] . "server" . self::$html_tags['param'][1] . ' = ' . self::$html_tags['val'][0] . $group['server_ip'] . self::$html_tags['val'][1] . ' # NAS ip must be in that range'
      :
      '	server = '. $group['server_ip']);
			///USER GROUP DEFAULT SERVICE///
			$default_service = ($group['default_service']) ? 'permit' : 'deny';
			array_push($outputUserGroup[$group['id']],
			($html) ? '	' . self::$html_tags['param'][0] . "default service" . self::$html_tags['param'][1] . ' = ' . self::$html_tags['val'][0] .$default_service. self::$html_tags['val'][1]
			:
			'	default service = '. $default_service);
			///USER GROUP SERVICE SHELL///
			if ( $group['service'] != 0 ) {

        $outputUserGroup[$group['id']] = array_merge( $outputUserGroup[$group['id']],  self::tacService($html, $group['service'], true) );

			} else {
				array_push($outputUserGroup[$group['id']],
				($html) ? '	' . self::$html_tags['param'][0] . "service" . self::$html_tags['param'][1] . ' = ' . self::$html_tags['attr'][0] .'shell {'. self::$html_tags['attr'][1]
				:
				'	service = shell {');
				array_push($outputUserGroup[$group['id']],
				($html) ? '		' . self::$html_tags['param'][0] . "default cmd" . self::$html_tags['param'][1] . ' = ' . self::$html_tags['val'][0] .'permit'. self::$html_tags['val'][1]
				:
				'		default cmd = permit');
				array_push($outputUserGroup[$group['id']],
				($html) ? '		' . self::$html_tags['param'][0] . "set priv-lvl" . self::$html_tags['param'][1] . ' = ' . self::$html_tags['val'][0] .$group['priv-lvl']. self::$html_tags['val'][1]
				:
				'		set priv-lvl = '.$group['priv-lvl']);
				array_push($outputUserGroup[$group['id']],
				($html) ? '	' . self::$html_tags['attr'][0] .'}'. self::$html_tags['attr'][1]
				:
				'	}');
			}
			///USER GROUP MANUAL CONFIGURATION///
			if ($group['manual']!="")
			{
				array_push($outputUserGroup[$group['id']], ($html) ? self::$html_tags['comment'][0] . '###MANUAL CONFIGURATION START###' . self::$html_tags['comment'][1]
				:
				'###MANUAL CONFIGURATION START###');
				$arrayManual=explode(PHP_EOL, $group['manual']);
				foreach($arrayManual as $item)
				{
					array_push($outputUserGroup[$group['id']], $item);
				}
				array_push($outputUserGroup[$group['id']], ($html) ? self::$html_tags['comment'][0] . '###MANUAL CONFIGURATION END###' . self::$html_tags['comment'][1]
				:
				'###MANUAL CONFIGURATION END###');
			}

			array_push($outputUserGroup[$group['id']],
			($html) ? '} ' . self::$html_tags['comment'][0] . '#END OF '.$group['name'] . self::$html_tags['comment'][1]
			:
			'} #END OF '.$group['name']);

		}

		return $outputUserGroup;
	}

	public static function tacUsersPartGen($html = false, $id = 0)
	{

		$allUsers = ($id == 0) ? TACUsers::select()->get()->toArray() : TACUsers::select()->where('id', $id)->get()->toArray();

		$allACL_array = TACACL::select('id','name')->where([['line_number','=',0]])->get()->toArray();
		$allUserGroups_array = TACUserGrps::select('id','name')->get()->toArray();
		$allACL = array();
		$allUserGroups = array();
		foreach($allACL_array as $acl)
		{
			$allACL[$acl['id']]=$acl['name'];
		}

		foreach($allUserGroups_array as $ugrp)
		{
			$allUserGroups[$ugrp['id']]=$ugrp['name'];
		}

		if ($id == 0) $outputUsers[0][0]=array('title_flag' => 1, 'name' =>
		($html) ? self::$html_tags['comment'][0] . "####LIST OF USERS####" . self::$html_tags['comment'][1]
		:
		"####LIST OF USERS####");
		foreach($allUsers as $user)
		{
			if ($user['disabled'] == 1 AND $id == 0) continue;
			///EMPTY ARRAY///
			$outputUsers[$user['id']] = array();
			///USER TITLE///
			$outputUsers[$user['id']][0] = array('title_flag' => 0, 'name' =>"");
			///USER NAME///
			array_push($outputUsers[$user['id']],
			($html) ? self::$html_tags['attr'][0] . "user" . self::$html_tags['attr'][1] . ' = ' . self::$html_tags['object'][0] .$user['username']. self::$html_tags['object'][1] . ' {'
			:
			'user = '.$user['username'].' {');
			///USER KEY///
			$login = self::$crypto_flag[$user['login_flag']].' '. ( ($user['login_flag'] != 3 ) ? $user['login'] : '#local' );
			if ($user['mavis_otp_enabled'] == 1 OR $user['mavis_sms_enabled'] == 1) $login = 'mavis';
			array_push($outputUsers[$user['id']],
			($html) ? '	'.self::$html_tags['param'][0] . "login" . self::$html_tags['param'][1] . ' = ' . self::$html_tags['val'][0] . $login . self::$html_tags['val'][1]
			:
			'	login = '. $login);
      ///USER MEMBER///
			if ($user['group'] > 0)array_push($outputUsers[$user['id']],
			($html) ? '	'.self::$html_tags['param'][0] . "member" . self::$html_tags['param'][1] . ' = ' . self::$html_tags['val'][0] . $allUserGroups[$user['group']] . self::$html_tags['val'][1]
			:
			'	member = '.$allUserGroups[$user['group']]);
			///USER PAP///
			// if ($user['pap_clone'] == 1) array_push($outputUsers[$user['id']],
			// ($html) ? '	'.self::$html_tags['param'][0] . "pap" . self::$html_tags['param'][1] . ' = ' . self::$html_tags['val'][0] . $login . self::$html_tags['val'][1]
			// :
			// '	pap = '. $login);
			if ( $user['pap_flag'] == 3 ) $user['pap'] = ' #local';
			if ( $user['pap'] != '' OR $user['pap_flag'] == 3 OR $user['pap_flag'] == 4 ) array_push($outputUsers[$user['id']],
			($html) ? '	'.self::$html_tags['param'][0] . "pap" . self::$html_tags['param'][1] . ' = ' . self::$html_tags['val'][0] . self::$crypto_flag[$user['pap_flag']].' '. ( ( $user['pap_flag'] != 4 ) ? $user['pap'] : '' ) . self::$html_tags['val'][1]
			:
			'	pap = '. self::$crypto_flag[$user['pap_flag']].' '. ( ($user['pap_flag'] != 4 ) ? $user['pap'] : '' ) );
			///USER CHAP///
			if (!empty($user['chap'])) array_push($outputUsers[$user['id']],
			($html) ? '	'.self::$html_tags['param'][0] . "chap" . self::$html_tags['param'][1] . ' = ' . self::$html_tags['val'][0] . 'clear '.$user['chap'] . self::$html_tags['val'][1]
			:
			'	chap = '. 'clear '.$user['chap']);
			///USER MS-CHAP///
			if (!empty($user['ms-chap'])) array_push($outputUsers[$user['id']],
			($html) ? '	'.self::$html_tags['param'][0] . "ms-chap" . self::$html_tags['param'][1] . ' = ' . self::$html_tags['val'][0] . 'clear '.$user['ms-chap'] . self::$html_tags['val'][1]
			:
			'	ms-chap = '. 'clear '.$user['ms-chap']);
			///USER ENABLE///
			if ( $user['enable_flag'] == 3 ) $user['enable'] = ' #local';
			if ($user['enable'] != '' OR $user['enable_flag'] == 4 ) array_push($outputUsers[$user['id']],
			($html) ? '	'.self::$html_tags['param'][0] . "enable" . self::$html_tags['param'][1] . ' = ' . self::$html_tags['val'][0] . self::$crypto_flag[$user['enable_flag']].' '. ( ($user['enable_flag'] != 4 ) ? $user['enable'] : '') . self::$html_tags['val'][1]
			:
			'	enable = '.self::$crypto_flag[$user['enable_flag']].' '. ( ($user['enable_flag'] != 4 ) ? $user['enable'] : '') );
			///USER ACL///
			if ($user['acl'] > 0)array_push($outputUsers[$user['id']],
			($html) ? '	'.self::$html_tags['param'][0] . "acl" . self::$html_tags['param'][1] . ' = ' . self::$html_tags['val'][0] . $allACL[$user['acl']] . self::$html_tags['val'][1]
			:
			'	acl = '. $allACL[$user['acl']]);
			///USER MESSAGE///
			///USER CLIENT IP///
			if ($user['client_ip'] > 0)array_push($outputUsers[$user['id']],
			($html) ? '	'.self::$html_tags['param'][0] . "client" . self::$html_tags['param'][1] . ' = ' . self::$html_tags['val'][0] . $user['client_ip'] . self::$html_tags['val'][1] . ' # NAC ip must be in that range'
			:
			'	client = '. $user['client_ip']);
			///USER SERVER IP///
			if ($user['server_ip'] > 0)array_push($outputUsers[$user['id']],
			($html) ? '	'.self::$html_tags['param'][0] . "server" . self::$html_tags['param'][1] . ' = ' . self::$html_tags['val'][0] . $user['server_ip'] . self::$html_tags['val'][1] . ' # NAS ip must be in that range'
			:
			'	server = '. $user['server_ip']);
			///USER MESSAGE///
			if ($user['message']!='')array_push($outputUsers[$user['id']],
			($html) ? '	'.self::$html_tags['param'][0] . "message" . self::$html_tags['param'][1] . ' = ' . self::$html_tags['val'][0] .'"'.$user['message'].'"'. self::$html_tags['val'][1]
			:
			'	message = "'.$user['message'].'"');
      ///USER Valid From///
      if ($user['valid_from']!='')array_push($outputUsers[$user['id']],
			($html) ? '	'.self::$html_tags['param'][0] . "valid from" . self::$html_tags['param'][1] . ' = ' . self::$html_tags['val'][0] .strtotime($user['valid_from']). self::$html_tags['val'][1] .' # '.$user['valid_from']
			:
			'	valid from = '.strtotime($user['valid_from']).' # '.$user['valid_from']);
      ///USER Valid Until///
      if ($user['valid_until']!='')array_push($outputUsers[$user['id']],
			($html) ? '	'.self::$html_tags['param'][0] . "valid until" . self::$html_tags['param'][1] . ' = ' . self::$html_tags['val'][0] .strtotime($user['valid_until']). self::$html_tags['val'][1] .' # '.$user['valid_until']
			:
			'	valid until = '.strtotime($user['valid_until']).' # '.$user['valid_until']);
			///USER SERVICE SHELL///
			if ($user['service'] == 0 AND  $user['group'] == 0) {
				///USER DEFAULT SERVICE///
				$default_service = ($user['default_service']) ? 'permit' : 'deny';
				array_push($outputUsers[$user['id']],
				($html) ? '	' . self::$html_tags['param'][0] . "default service" . self::$html_tags['param'][1] . ' = ' . self::$html_tags['val'][0] .$default_service. self::$html_tags['val'][1]
				:
				'	default service = '. $default_service);
				///////////////////////////////////////////
				if ($user['priv-lvl'] < 0) $user['priv-lvl'] = 15;
				array_push($outputUsers[$user['id']],
				($html) ? '	' . self::$html_tags['param'][0] . "service" . self::$html_tags['param'][1] . ' = ' . self::$html_tags['attr'][0] .'shell {'. self::$html_tags['attr'][1]
				:
				'	service = shell {');
				array_push($outputUsers[$user['id']],
				($html) ? '		' . self::$html_tags['param'][0] . "default cmd" . self::$html_tags['param'][1] . ' = ' . self::$html_tags['val'][0] .'permit'. self::$html_tags['val'][1]
				:
				'		default cmd = permit');
				array_push($outputUsers[$user['id']],
				($html) ? '		' . self::$html_tags['param'][0] . "set priv-lvl" . self::$html_tags['param'][1] . ' = ' . self::$html_tags['val'][0] .$user['priv-lvl']. self::$html_tags['val'][1]
				:
				'		set priv-lvl = '.$user['priv-lvl']);
				array_push($outputUsers[$user['id']],
				($html) ? '	' . self::$html_tags['attr'][0] .'}'. self::$html_tags['attr'][1]
				:
				'	}');
			}
			if ($user['service'] != 0) {

				$outputUsers[$user['id']] = array_merge( $outputUsers[$user['id']],  self::tacService($html, $user['service'], true) );

			}
			elseif($user['group'] != 0){
				array_push($outputUsers[$user['id']],
				($html) ? '	' . self::$html_tags['comment'][0] . "### GET SERVICES FROM GROUP" . self::$html_tags['comment'][1]
				:
				'	### GET SERVICES FROM GROUP');
			}
			///USER MANUAL CONFIGURATION///
			if ($user['manual']!="")
			{
				array_push($outputUsers[$user['id']], ($html) ? self::$html_tags['comment'][0] . '###MANUAL CONFIGURATION START###' . self::$html_tags['comment'][1]
				:
				'###MANUAL CONFIGURATION START###');
				$arrayManual=explode(PHP_EOL, $user['manual']);
				foreach($arrayManual as $item)
				{
					array_push($outputUsers[$user['id']], $item);
				}
				array_push($outputUsers[$user['id']], ($html) ? self::$html_tags['comment'][0] . '###MANUAL CONFIGURATION END###' . self::$html_tags['comment'][1]
				:
				'###MANUAL CONFIGURATION END###');
			}

			array_push($outputUsers[$user['id']],
			($html) ? '} ' . self::$html_tags['comment'][0] . '#END OF '.$user['username'] . self::$html_tags['comment'][1]
			:
			'} #END OF '.$user['username']);

		}

		return $outputUsers;
	}

	public static function tacMavisLdapGen($html)
	{
		$html = (empty($html)) ? false : true;

		$mavis_ldap_settings = MAVISLDAP::select()->first();

		if ($mavis_ldap_settings->enabled == 0) return array('title_flag' => 0, 'name' =>"");

		$id = $mavis_ldap_settings->id;

		$outputMavisLdap[0][0]=array('title_flag' => 1, 'name' =>
		($html) ? self::$html_tags['comment'][0] . "####MAVIS LDAP MODULE####" . self::$html_tags['comment'][1]
		:
		"####MAVIS LDAP MODULE####");
		///EMPTY ARRAY///
		$outputMavisLdap[$id] = array();
		///MAVIS LDAP TITLE///
		$outputMavisLdap[$id][0] = array('title_flag' => 0, 'name' =>"");
		///MAVIS LDAP SETTINGS START///
		array_push($outputMavisLdap[$id],
		($html) ? self::$html_tags['attr'][0] . "mavis module" . self::$html_tags['attr'][1] . ' = ' . self::$html_tags['object'][0] . 'external' . self::$html_tags['object'][1] . ' {'
		:
		'mavis module = external {');
		///LDAP SERVER TYPE///

		array_push($outputMavisLdap[$id],
		($html) ? self::$html_tags['param'][0] . "	setenv LDAP_SERVER_TYPE" . self::$html_tags['param'][1] . ' = ' . self::$html_tags['val'][0] .'"'. $mavis_ldap_settings['type'] .'"'. self::$html_tags['val'][1]
		:
		'	setenv LDAP_SERVER_TYPE = "'. $mavis_ldap_settings['type'].'"');
		///LDAP HOSTS///
		array_push($outputMavisLdap[$id],
		($html) ? self::$html_tags['param'][0] . "	setenv LDAP_HOSTS" . self::$html_tags['param'][1] . ' = ' . self::$html_tags['val'][0] .'"'. $mavis_ldap_settings['hosts'] .'"'. self::$html_tags['val'][1]
		:
		'	setenv LDAP_HOSTS = "'. $mavis_ldap_settings['hosts'].'"');
		///LDAP SCOPE///
		array_push($outputMavisLdap[$id],
		($html) ? self::$html_tags['param'][0] . "	setenv LDAP_SCOPE" . self::$html_tags['param'][1] . ' = ' . self::$html_tags['val'][0] . $mavis_ldap_settings['scope'] . self::$html_tags['val'][1]
		:
		'	setenv LDAP_SCOPE = '. $mavis_ldap_settings['scope']);
		///LDAP BASE///
		array_push($outputMavisLdap[$id],
		($html) ? self::$html_tags['param'][0] . "	setenv LDAP_BASE" . self::$html_tags['param'][1] . ' = ' . self::$html_tags['val'][0] .'"'. $mavis_ldap_settings['base'] .'"'. self::$html_tags['val'][1]
		:
		'	setenv LDAP_BASE = "'. $mavis_ldap_settings['base'].'"');
		///LDAP FILTER///
		array_push($outputMavisLdap[$id],
		($html) ? self::$html_tags['param'][0] . "	setenv LDAP_FILTER" . self::$html_tags['param'][1] . ' = ' . self::$html_tags['val'][0] .'"'. $mavis_ldap_settings['filter'] .'"'. self::$html_tags['val'][1]
		:
		'	setenv LDAP_FILTER = "'. $mavis_ldap_settings['filter'].'"');
		///LDAP USER///
		array_push($outputMavisLdap[$id],
		($html) ? self::$html_tags['param'][0] . "	setenv LDAP_USER" . self::$html_tags['param'][1] . ' = ' . self::$html_tags['val'][0] .'"'. $mavis_ldap_settings['user'] .'"'. self::$html_tags['val'][1]
		:
		'	setenv LDAP_USER = "'. $mavis_ldap_settings['user'].'"');
		///LDAP PASSWD///
		array_push($outputMavisLdap[$id],
		($html) ? self::$html_tags['param'][0] . "	setenv LDAP_PASSWD" . self::$html_tags['param'][1] . ' = ' . self::$html_tags['val'][0] .'"'. ( (!$mavis_ldap_settings['password_hide'] ) ? $mavis_ldap_settings['password'] : '********')  .'"'. self::$html_tags['val'][1]
		:
		'	setenv LDAP_PASSWD = "'. $mavis_ldap_settings['password'].'"');
		///LDAP AD GROUP PREFIX///
		$commentChar = ($mavis_ldap_settings['group_prefix'] == '') ? '#' : '';
		array_push($outputMavisLdap[$id],
		($html) ?  $commentChar . self::$html_tags['param'][0] . "	setenv AD_GROUP_PREFIX" . self::$html_tags['param'][1] . ' = ' . self::$html_tags['val'][0] . $mavis_ldap_settings['group_prefix'] . self::$html_tags['val'][1] . '# default prefix is <i>tacacs</i>'
		:
		$commentChar .'	setenv AD_GROUP_PREFIX = '. $mavis_ldap_settings['group_prefix']);
		///LDAP PRIFIX REQUIRED///
		array_push($outputMavisLdap[$id],
		($html) ? self::$html_tags['param'][0] . "	setenv REQUIRE_AD_GROUP_PREFIX" . self::$html_tags['param'][1] . ' = ' . self::$html_tags['val'][0] . $mavis_ldap_settings['group_prefix_flag'] . self::$html_tags['val'][1]
		:
		'	setenv REQUIRE_AD_GROUP_PREFIX = '. $mavis_ldap_settings['group_prefix_flag']);
		///LDAP TLS///
		array_push($outputMavisLdap[$id],
		($html) ? ( ($mavis_ldap_settings['tls']) ? '': '#') . self::$html_tags['param'][0] . "	setenv USE_TLS" . self::$html_tags['param'][1] . ' = ' . self::$html_tags['val'][0] . $mavis_ldap_settings['tls'] . self::$html_tags['val'][1]
		:
		( ($mavis_ldap_settings['tls']) ? '': '#') . '	setenv USE_TLS = '. $mavis_ldap_settings['tls']);
		///LDAP CACHE CONN///
		array_push($outputMavisLdap[$id],
		($html) ? self::$html_tags['param'][0] . "	setenv FLAG_CACHE_CONNECTION" . self::$html_tags['param'][1] . ' = ' . self::$html_tags['val'][0] . $mavis_ldap_settings['cache_conn'] . self::$html_tags['val'][1]
		:
		'	setenv FLAG_CACHE_CONNECTION  = '. $mavis_ldap_settings['cache_conn']);
		///LDAP MEMBEROF///
		array_push($outputMavisLdap[$id],
		($html) ? self::$html_tags['param'][0] . "	setenv FLAG_USE_MEMBEROF" . self::$html_tags['param'][1] . ' = ' . self::$html_tags['val'][0] . $mavis_ldap_settings['memberOf'] . self::$html_tags['val'][1]
		:
		'	setenv FLAG_USE_MEMBEROF  = '. $mavis_ldap_settings['memberOf']);
		///LDAP FALLTHROUGH///
		array_push($outputMavisLdap[$id],
		($html) ? self::$html_tags['param'][0] . "	setenv FLAG_FALLTHROUGH" . self::$html_tags['param'][1] . ' = ' . self::$html_tags['val'][0] . $mavis_ldap_settings['fallthrough'] . self::$html_tags['val'][1]
		:
		'	setenv FLAG_FALLTHROUGH  = '. $mavis_ldap_settings['fallthrough']);
		///LDAP PATH///
		array_push($outputMavisLdap[$id],
		($html) ? self::$html_tags['param'][0] . "	exec" . self::$html_tags['param'][1] . ' = ' . self::$html_tags['val'][0] . $mavis_ldap_settings['path'] . self::$html_tags['val'][1]
		:
		'	exec  = '. $mavis_ldap_settings['path']);
		///USER MANUAL CONFIGURATION///
		/*if ($group['manual']!="")
		{
			array_push($outputUserGroup[$group['id']], ($html) ? self::$html_tags['comment'][0] . '###MANUAL CONFIGURATION START###' . self::$html_tags['comment'][1]
			:
			'###MANUAL CONFIGURATION START###');
			$arrayManual=explode(PHP_EOL, $group['manual']);
			foreach($arrayManual as $item)
			{
				array_push($outputUserGroup[$group['id']], $item);
			}
			array_push($outputUserGroup[$group['id']], ($html) ? self::$html_tags['comment'][0] . '###MANUAL CONFIGURATION END###' . self::$html_tags['comment'][1]
			:
			'###MANUAL CONFIGURATION END###');
		}*/

		array_push($outputMavisLdap[$id],
		($html) ? '} ' . self::$html_tags['comment'][0] . '#END OF MAVIS LDAP SETTINGS' . self::$html_tags['comment'][1]
		:
		'} #END OF MAVIS LDAP SETTINGS');

		return $outputMavisLdap;
	}

	public static function tacMavisGeneralGen($html)
	{
		$html = (empty($html)) ? false : true;

		$mavis_ldap_settings = MAVISLDAP::select()->first();
		$mavis_otp_settings = MAVISOTP::select()->first();
		$mavis_sms_settings = MAVISSMS::select()->first();
		$mavis_local_settings = MAVISLocal::select()->first();

		if ($mavis_ldap_settings->enabled == 0 AND $mavis_otp_settings->enabled == 0 AND $mavis_sms_settings->enabled == 0 AND $mavis_local_settings->enabled == 0) return array('title_flag' => 0, 'name' =>"");

		$outputMavisGeneral[0][0]=array('title_flag' => 1, 'name' =>
		($html) ? self::$html_tags['comment'][0] . "####MAVIS GENERAL SETTINGS####" . self::$html_tags['comment'][1]
		:
		"####MAVIS GENERAL SETTINGS####");
		///EMPTY ARRAY///
		$outputMavisGeneral[1] = array();
		///MAVIS GENERAL TITLE///
		$outputMavisGeneral[1][0] = array('title_flag' => 0, 'name' =>"");
		///MAVIS GENERAL SETTINGS START///
		array_push($outputMavisGeneral[1],
		($html) ? self::$html_tags['attr'][0] . "user backend" . self::$html_tags['attr'][1] . ' = ' . self::$html_tags['object'][0] . 'mavis' . self::$html_tags['object'][1]
		:
		'user backend = mavis');
		array_push($outputMavisGeneral[1],
		($html) ? self::$html_tags['attr'][0] . "login backend" . self::$html_tags['attr'][1] . ' = ' . self::$html_tags['object'][0] . 'mavis'. ( ($mavis_local_settings->enabled == 1 AND $mavis_local_settings->enabled == 1) ? ' chpass' : '' ) .' #chalresp' . self::$html_tags['object'][1]
		:
		'login backend = mavis'. ( ($mavis_local_settings->enabled == 1 AND $mavis_local_settings->enabled == 1) ? ' chpass' : '' ) .' #chalresp');
		array_push($outputMavisGeneral[1],
		($html) ? self::$html_tags['attr'][0] . "pap backend" . self::$html_tags['attr'][1] . ' = ' . self::$html_tags['object'][0] . 'mavis' . self::$html_tags['object'][1]
		:
		'pap backend = mavis');

	return $outputMavisGeneral;
	}

	public static function tacMavisOTPGen($html)
	{
		$html = (empty($html)) ? false : true;

		$mavis_otp_settings = MAVISOTP::select('enabled')->first();

		if ($mavis_otp_settings->enabled == 0) return array('title_flag' => 0, 'name' =>"");

		$outputMavisOTP[0][0]=array('title_flag' => 1, 'name' =>
		($html) ? self::$html_tags['comment'][0] . "####MAVIS OTP SETTINGS####" . self::$html_tags['comment'][1]
		:
		"####MAVIS OTP SETTINGS####");
		///EMPTY ARRAY///
		$outputMavisOTP[1] = array();
		///MAVIS OTP TITLE///
		$outputMavisOTP[1][0] = array('title_flag' => 0, 'name' =>"");
		///MAVIS OTP SETTINGS START///
		array_push($outputMavisOTP[1],
		($html) ? self::$html_tags['attr'][0] . "mavis module" . self::$html_tags['attr'][1] . ' = ' . self::$html_tags['object'][0] . 'external' . self::$html_tags['object'][1] . ' {'
		:
		'mavis module = external {');

		///OTP PATH///
		array_push($outputMavisOTP[1],
		($html) ? self::$html_tags['param'][0] . "	exec" . self::$html_tags['param'][1] . ' = ' . self::$html_tags['val'][0] . TAC_ROOT_PATH . '/mavis-modules/otp/module.php' . self::$html_tags['val'][1]
		:
		'	exec  = ' . TAC_ROOT_PATH . '/mavis-modules/otp/module.php');

		array_push($outputMavisOTP[1],
		($html) ? '} ' . self::$html_tags['comment'][0] . '#END OF MAVIS OTP SETTINGS' . self::$html_tags['comment'][1]
		:
		'} #END OF MAVIS OTP SETTINGS');



	return $outputMavisOTP;
	}

	////MAVIS SMS////
	public static function tacMavisSMSGen($html)
	{
		$html = (empty($html)) ? false : true;

		$mavis_sms_settings = MAVISSMS::select('enabled')->first();

		if ($mavis_sms_settings->enabled == 0) return array('title_flag' => 0, 'name' =>"");

		$outputMavisSMS[0][0]=array('title_flag' => 1, 'name' =>
		($html) ? self::$html_tags['comment'][0] . "####MAVIS SMS SETTINGS####" . self::$html_tags['comment'][1]
		:
		"####MAVIS SMS SETTINGS####");
		///EMPTY ARRAY///
		$outputMavisSMS[1] = array();
		///MAVIS SMS TITLE///
		$outputMavisSMS[1][0] = array('title_flag' => 0, 'name' =>"");
		///MAVIS SMS SETTINGS START///
		array_push($outputMavisSMS[1],
		($html) ? self::$html_tags['attr'][0] . "mavis module" . self::$html_tags['attr'][1] . ' = ' . self::$html_tags['object'][0] . 'external' . self::$html_tags['object'][1] . ' {'
		:
		'mavis module = external {');

		///SMS PATH///
		array_push($outputMavisSMS[1],
		($html) ? self::$html_tags['param'][0] . "	exec" . self::$html_tags['param'][1] . ' = ' . self::$html_tags['val'][0] . TAC_ROOT_PATH . '/mavis-modules/sms/module.php' . self::$html_tags['val'][1]
		:
		'	exec  = ' . TAC_ROOT_PATH . '/mavis-modules/sms/module.php');

		array_push($outputMavisSMS[1],
		($html) ? '} ' . self::$html_tags['comment'][0] . '#END OF MAVIS SMS SETTINGS' . self::$html_tags['comment'][1]
		:
		'} #END OF MAVIS SMS SETTINGS');

	return $outputMavisSMS;
	}

	////MAVIS Local////
	public static function tacMavisLocal($html)
	{
		$html = (empty($html)) ? false : true;

		$mavis_local_settings = MAVISLocal::select('enabled')->first();

		if ($mavis_local_settings->enabled == 0) return array('title_flag' => 0, 'name' =>"");

		$outputMavisLocal[0][0]=array('title_flag' => 1, 'name' =>
		($html) ? self::$html_tags['comment'][0] . "####MAVIS Local DB SETTINGS####" . self::$html_tags['comment'][1]
		:
		"####MAVIS Local DB SETTINGS####");
		///EMPTY ARRAY///
		$outputMavisLocal[1] = array();
		///MAVIS Local TITLE///
		$outputMavisLocal[1][0] = array('title_flag' => 0, 'name' =>"");
		///MAVIS Local SETTINGS START///
		array_push($outputMavisLocal[1],
		($html) ? self::$html_tags['attr'][0] . "mavis module" . self::$html_tags['attr'][1] . ' = ' . self::$html_tags['object'][0] . 'external' . self::$html_tags['object'][1] . ' {'
		:
		'mavis module = external {');

		///Local PATH///
		array_push($outputMavisLocal[1],
		($html) ? self::$html_tags['param'][0] . "	exec" . self::$html_tags['param'][1] . ' = ' . self::$html_tags['val'][0] . TAC_ROOT_PATH . '/mavis-modules/local/module.php' . self::$html_tags['val'][1]
		:
		'	exec  = ' . TAC_ROOT_PATH . '/mavis-modules/local/module.php');

		array_push($outputMavisLocal[1],
		($html) ? '} ' . self::$html_tags['comment'][0] . '#END OF MAVIS Local DB SETTINGS' . self::$html_tags['comment'][1]
		:
		'} #END OF MAVIS Local DB SETTINGS');

	return $outputMavisLocal;
	}
  public static function tacService($html = false, $id = 0, $noPreview = false)
	{
    if ( $id == 0 ) return [];
    $service = TACServices::select()->where('id', $id)->first()->toArray();

			///EMPTY ARRAY///
			$outputService[$service['id']] = array();
			///Service TITLE///
			if ( ! $noPreview ) $outputService[$service['id']][0] = array('title_flag' => 0, 'name' =>"");
			array_push($outputService[$service['id']], ($html) ? self::$html_tags['comment'][0] . '###Service '.$service['name'].' START###' . self::$html_tags['comment'][1]
			:
			'###Service '.$service['name'].' START###');

      if( ! $service['manual_conf_only'] ){

        ///Cisco RS///START///
        if ( $service['cisco_rs_enable'] ) {
          //start//
          array_push($outputService[$service['id']],
    			($html) ? self::$html_tags['attr'][0] . "service " . self::$html_tags['attr'][1] . ' = ' . self::$html_tags['object'][0]. 'shell' . self::$html_tags['object'][1] . ' {'
    			:
    			'service = shell {');

          $autoCmd = explode( ';;', $service['cisco_rs_autocmd'] );
          for ($i=0; $i < count($autoCmd); $i++) {
            if ( empty($autoCmd[$i]) ) continue;

            array_push($outputService[$service['id']],
      			($html) ? '	'.self::$html_tags['param'][0] . "set autocmd" . self::$html_tags['param'][1] . ' = "' . self::$html_tags['val'][0] . $autoCmd[$i] . self::$html_tags['val'][1] . '"'
      			:
      			'set autocmd = "' . $autoCmd[$i] . '"');
          }

          if ( !empty($service['cisco_rs_privlvl']) ) array_push($outputService[$service['id']],
    			($html) ? '	'.self::$html_tags['param'][0] . "set priv-lvl" . self::$html_tags['param'][1] . ' = ' . self::$html_tags['val'][0] . $service['cisco_rs_privlvl'] . self::$html_tags['val'][1]
    			:
    			'set priv-lvl = '.$service['cisco_rs_privlvl']);

          if ( !empty($service['cisco_rs_def_attr']) ) array_push($outputService[$service['id']],
    			($html) ? '	'.self::$html_tags['param'][0] . "default attribute" . self::$html_tags['param'][1] . ' = ' . self::$html_tags['val'][0] . 'permit' . self::$html_tags['val'][1]
    			:
    			'default attribute = permit');
          if ( !empty($service['cisco_rs_def_cmd']) ) array_push($outputService[$service['id']],
    			($html) ? '	'.self::$html_tags['param'][0] . "default cmd" . self::$html_tags['param'][1] . ' = ' . self::$html_tags['val'][0] . 'permit' . self::$html_tags['val'][1]
    			:
    			'default cmd = permit');
          if ( !empty($service['cisco_rs_idletime']) ) array_push($outputService[$service['id']],
    			($html) ? '	'.self::$html_tags['param'][0] . "set idletime" . self::$html_tags['param'][1] . ' = ' . self::$html_tags['val'][0] . $service['cisco_rs_idletime'] . self::$html_tags['val'][1]
    			:
    			'set idletime = ' . $service['cisco_rs_idletime']);
          if ( !empty($service['cisco_rs_timeout']) ) array_push($outputService[$service['id']],
    			($html) ? '	'.self::$html_tags['param'][0] . "set timeout" . self::$html_tags['param'][1] . ' = ' . self::$html_tags['val'][0] . $service['cisco_rs_timeout'] . self::$html_tags['val'][1]
    			:
    			'set timeout = ' . $service['cisco_rs_timeout']);

          if ( !empty($service['cisco_rs_cmd']) ){

            $cmdIdList = explode( ';;', $service['cisco_rs_cmd'] );

            $outputService[$service['id']] = array_merge( $outputService[$service['id']],  self::tacCMDAttr($html, $cmdIdList, 'cisco') );

          }

          if ( !empty($service['cisco_rs_debug_message']) ) array_push($outputService[$service['id']],
    			($html) ? '	'.self::$html_tags['param'][0] . "message debug" . self::$html_tags['param'][1] . ' = ' . self::$html_tags['val'][0] . '"to permit: cmd = %c { permit /^%a$/ } '."\n".'to deny: cmd = %c { deny /^%a$/ }"' . self::$html_tags['val'][1]
    			:
    			'message debug = ' . '"to permit: cmd = %c { permit /^%a$/ } '."\n".'to deny: cmd = %c { deny /^%a$/ }"');

          $outputService[$service['id']] = array_merge( $outputService[$service['id']],  self::manualConfigPrint($service['cisco_rs_manual'], $html) );

          //end//
          array_push($outputService[$service['id']],
    			($html) ? '} ' . self::$html_tags['comment'][0] . '#END OF Cisco Router/Switch Service'. self::$html_tags['comment'][1]
    			:
    			'} #END OF Cisco Router/Switch Service');
        }
        ///Cisco RS///END///
        ///Cisco WLC///START///
        if ( $service['cisco_wlc_enable'] ) {
          //start//
          array_push($outputService[$service['id']],
    			($html) ? self::$html_tags['attr'][0] . "service " . self::$html_tags['attr'][1] . ' = ' . self::$html_tags['object'][0]. 'ciscowlc' . self::$html_tags['object'][1] . ' {'
    			:
    			'service = ciscowlc {');

          $roles = explode( ';;', $service['cisco_wlc_roles'] );

          for ($i=0; $i < count($roles); $i++) {
            if (! in_array($roles[$i], array_keys(self::$ciscoWLCRoles) ) ) continue;

            array_push($outputService[$service['id']],
      			($html) ? '	'.self::$html_tags['param'][0] . "set role". ($i + 1) . self::$html_tags['param'][1] . ' = ' . self::$html_tags['val'][0] . self::$ciscoWLCRoles[$roles[$i]] . self::$html_tags['val'][1]
      			:
      			'set role'. ($i + 1) .' = ' . self::$ciscoWLCRoles[$roles[$i]] );
          }

          $outputService[$service['id']] = array_merge( $outputService[$service['id']],  self::manualConfigPrint($service['cisco_wlc_manual'], $html) );

          //end//
          array_push($outputService[$service['id']],
    			($html) ? '} ' . self::$html_tags['comment'][0] . '#END OF Cisco WLC Service'. self::$html_tags['comment'][1]
    			:
    			'} #END OF Cisco WLC Service');
        }
        ///Cisco WLC///END///
        ///FortiOS///START///
        if ( $service['fortios_enable'] ) {
          //start//
          array_push($outputService[$service['id']],
    			($html) ? self::$html_tags['attr'][0] . "service " . self::$html_tags['attr'][1] . ' = ' . self::$html_tags['object'][0]. 'fortigate' . self::$html_tags['object'][1] . ' {'
    			:
    			'service = fortigate {');

          if (!empty($service['fortios_admin_prof'])) array_push($outputService[$service['id']],
    			($html) ? '	'.self::$html_tags['param'][0] . "optional admin_prof" . self::$html_tags['param'][1] . ' = ' . self::$html_tags['val'][0] . $service['fortios_admin_prof'] . self::$html_tags['val'][1]
    			:
    			'optional admin_prof = '.$service['fortios_admin_prof']);

          $outputService[$service['id']] = array_merge( $outputService[$service['id']],  self::manualConfigPrint($service['fortios_manual'], $html) );

          //end//
          array_push($outputService[$service['id']],
    			($html) ? '} ' . self::$html_tags['comment'][0] . '#END OF FortiOS Service'. self::$html_tags['comment'][1]
    			:
    			'} #END OF FortiOS Service');
        }
        ///FortiOS///END///
        ///PaloALto///START///
        if ( $service['paloalto_enable'] ) {
          //start//
          array_push($outputService[$service['id']],
    			($html) ? self::$html_tags['attr'][0] . "service " . self::$html_tags['attr'][1] . ' = ' . self::$html_tags['object'][0]. 'PaloAlto' . self::$html_tags['object'][1] . ' {'
    			:
    			'service = PaloAlto {');

          array_push($outputService[$service['id']],
    			($html) ? self::$html_tags['object'][0] . "set protocol = firewall" . self::$html_tags['object'][1] . self::$html_tags['comment'][0] . " #default settings" . self::$html_tags['comment'][1]
    			:
    			'set protocol = firewall #default settings');

          if (!empty($service['paloalto_admin_role'])) array_push($outputService[$service['id']],
    			($html) ? '	'.self::$html_tags['param'][0] . "set PaloAlto-Admin-Role" . self::$html_tags['param'][1] . ' = ' . self::$html_tags['val'][0] . $service['paloalto_admin_role'] . self::$html_tags['val'][1]
    			:
    			'set PaloAlto-Admin-Role = '.$service['paloalto_admin_role']);

          if (!empty($service['paloalto_admin_domain'])) array_push($outputService[$service['id']],
    			($html) ? '	'.self::$html_tags['param'][0] . "set PaloAlto-Admin-Access-Domain" . self::$html_tags['param'][1] . ' = ' . self::$html_tags['val'][0] . $service['paloalto_admin_domain'] . self::$html_tags['val'][1]
    			:
    			'set PaloAlto-Admin-Access-Domain = '.$service['paloalto_admin_domain']);

          if (!empty($service['paloalto_panorama_admin_role'])) array_push($outputService[$service['id']],
    			($html) ? '	'.self::$html_tags['param'][0] . "set PaloAlto-Panorama-Admin-Role" . self::$html_tags['param'][1] . ' = ' . self::$html_tags['val'][0] . $service['paloalto_panorama_admin_role'] . self::$html_tags['val'][1]
    			:
    			'set PaloAlto-Panorama-Admin-Role = '.$service['paloalto_panorama_admin_role']);

          if (!empty($service['paloalto_panorama_admin_domain'])) array_push($outputService[$service['id']],
    			($html) ? '	'.self::$html_tags['param'][0] . "set PaloAlto-Panorama-Admin-Access-Domain" . self::$html_tags['param'][1] . ' = ' . self::$html_tags['val'][0] . $service['paloalto_panorama_admin_domain'] . self::$html_tags['val'][1]
    			:
    			'set PaloAlto-Panorama-Admin-Access-Domain = '.$service['paloalto_panorama_admin_domain']);

          if (!empty($service['paloalto_user_group'])) array_push($outputService[$service['id']],
    			($html) ? '	'.self::$html_tags['param'][0] . "set PaloAlto-User-Group" . self::$html_tags['param'][1] . ' = ' . self::$html_tags['val'][0] . $service['paloalto_user_group'] . self::$html_tags['val'][1]
    			:
    			'set PaloAlto-User-Group = '.$service['paloalto_user_group']);

          $outputService[$service['id']] = array_merge( $outputService[$service['id']],  self::manualConfigPrint($service['paloalto_manual'], $html) );

          //end//
          array_push($outputService[$service['id']],
    			($html) ? '} ' . self::$html_tags['comment'][0] . '#END OF PaloAlto Service'. self::$html_tags['comment'][1]
    			:
    			'} #END OF PaloAlto Service');
        }
        ///PaloAlto///END///
      }


      $outputService[$service['id']] = array_merge( $outputService[$service['id']],  self::manualConfigPrint($service['manual'], $html) );

			array_push($outputService[$service['id']], ($html) ? self::$html_tags['comment'][0] . '###Service '.$service['name'].' END###' . self::$html_tags['comment'][1]
			:
			'###Service '.$service['name'].' END###');

		if ( $noPreview ) return $outputService[$service['id']]; else return $outputService;
	}

  public static function tacCMDAttr($html = false, $id = [], $type = '')
  {

    $cmdList = ( is_array($id) ) ? TACCMD::select()->where('type', $type)->whereIn('id', $id)->get() : TACCMD::select()->where('id', $id)->get();

    $onlyOne = ( !is_array($id) ) ;

    $outputCMDAttr[0] = array();

    for ($cl=0; $cl < count($cmdList); $cl++) {
      if ( empty($cmdList[$cl]) ) continue;
      $cmdId = ($onlyOne) ? $cmdList[$cl]->id : 0;
      ///EMPTY ARRAY///
      $outputCMDAttr[$cmdId] = ($onlyOne) ? array() : $outputCMDAttr[$cmdId];
      ///Service TITLE///
      if ($onlyOne) $outputCMDAttr[$cmdId][0] = array('title_flag' => 0, 'name' =>"");
      array_push($outputCMDAttr[$cmdId], ($html) ? self::$html_tags['comment'][0] . '###CMD Attr '.$cmdList[$cl]->name.' START###' . self::$html_tags['comment'][1]
      :
      '###CMD Attr '.$cmdList[$cl]->name.' START###');

      array_push($outputCMDAttr[$cmdId],
      ($html) ? '	'.self::$html_tags['param'][0] . "cmd" . self::$html_tags['param'][1] . ' = ' . self::$html_tags['val'][0] . $cmdList[$cl]->cmd . self::$html_tags['val'][1]. ' {'
      :
      'cmd = ' . $cmdList[$cl]->cmd . ' {');

      $cmdAttrList = explode( ';;', $cmdList[$cl]->cmd_attr );
      for ($al=0; $al < count($cmdAttrList); $al++) {
        if ( empty($cmdAttrList[$al]) ) continue;

        array_push($outputCMDAttr[$cmdId],
        ($html) ? '	'. $cmdAttrList[$al]
        :
        $cmdAttrList[$al]);
      }

      $outputCMDAttr[$cmdId] = array_merge( $outputCMDAttr[$cmdId],  self::manualConfigPrint($cmdList[$cl]->manual, $html) );

      if ( !empty($cmdList[$cl]->cmd_permit_end) ) array_push($outputCMDAttr[$cmdId],
      ($html) ? '	'.'permit .*'.self::$html_tags['comment'][0].' # default permit any'.self::$html_tags['comment'][1]
      :
      'permit .* # default permit any');
      if ( !empty($cmdList[$cl]->message_permit) ) array_push($outputCMDAttr[$cmdId],
      ($html) ? '	'.'message permit = "'.$cmdList[$cl]->message_permit.'"'
      :
      'message permit = "'.$cmdList[$cl]->message_permit.'"');
      if ( !empty($cmdList[$cl]->message_deny) ) array_push($outputCMDAttr[$cmdId],
      ($html) ? '	'.'message deny = "'.$cmdList[$cl]->message_deny.'"'
      :
      'message deny = "'.$cmdList[$cl]->message_deny.'"');

      array_push($outputCMDAttr[$cmdId],
      ($html) ? '	'.'} ' . self::$html_tags['comment'][0] . '#END CMD Attr ' . $cmdList[$cl]->name . self::$html_tags['comment'][1]
      :
      '} #END OF CMD Attr ' . $cmdList[$cl]->name );
    }

    if ($onlyOne) return $outputCMDAttr; else return $outputCMDAttr[0];
  }


  public static function manualConfigPrint($data = '', $html = false)
  {
    ///MANUAL CONFIGURATION///
    $output = [];
		if ( ! empty($data) )
		{
			array_push($output, ($html) ? self::$html_tags['comment'][0] . '###MANUAL CONFIGURATION START###' . self::$html_tags['comment'][1]
			:
			'###MANUAL CONFIGURATION START###');
			$arrayManual=explode(PHP_EOL, $data);
			foreach($arrayManual as $item)
			{
				array_push($output, $item);
			}
			array_push($output, ($html) ? self::$html_tags['comment'][0] . '###MANUAL CONFIGURATION END###' . self::$html_tags['comment'][1]
			:
			'###MANUAL CONFIGURATION END###');
		}
    return $output;
  }
}
