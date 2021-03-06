<?php

/**
 * WODE_CMS
 * =======================================================
 * 版权所有 (C) 2010-2020 www.wodecms.com，并保留所有权利。
 * 网站地址: http://www.wodecms.com
 * Q Q: 9877633
 * -------------------------------------------------------
 *
 * @author :     milkcy <milkcy@foxmail.com>
 * @version :    v1.0
 * =======================================================
 */ 
define ('XDB_VERSION',		34);		
define ('XDB_TAGNAME',		'XDB');		
define ('XDB_MAXKLEN',		0xf0);		
class XDB_R
{
	var $fd = false;
	var $hash_base = 0;
	var $hash_prime = 0;
	function __construct() { }
	function __destruct() { $this->Close(); }
	function Open($fpath)
	{
		$this->Close();
		if (!($fd = @fopen($fpath, 'rb')))
		{
			trigger_error("XDB::Open(" . basename($fpath) . ") failed.", E_USER_WARNING);
			return false;
		}
		if (!$this->_check_header($fd))
		{
			trigger_error("XDB::Open(" . basename($fpath) . "), invalid xdb format.", E_USER_WARNING);
			fclose($fd);
			return false;
		}
		$this->fd = $fd;
		return true;
	}
	function Get($key)
	{
		if (!$this->fd)
		{
			trigger_error("XDB:Get(), null db handler.", E_USER_WARNING);
			return false;
		}
		$klen = strlen($key);
		if ($klen == 0 || $klen > XDB_MAXKLEN)
		return false;
		$rec = $this->_get_record($key);
		if (!isset($rec['vlen']) || $rec['vlen'] == 0)
		return false;
		return $rec['value'];
	}
	function Close()
	{
		if (!$this->fd)
		return;
		fclose($this->fd);
		$this->fd = false;
	}
	function _get_index($key)
	{
		$l = strlen($key);
		$h = $this->hash_base;
		while ($l--)
		{
			$h += ($h << 5);
			$h ^= ord($key[$l]);
			$h &= 0x7fffffff;
		}
		return ($h % $this->hash_prime);
	}
	function _check_header($fd)
	{
		fseek($fd, 0, SEEK_SET);
		$buf = fread($fd, 32);
		if (strlen($buf) !== 32) return false;
		$hdr = unpack('a3tag/Cver/Ibase/Iprime/Ifsize/fcheck/a12reversed', $buf);
		if ($hdr['tag'] != XDB_TAGNAME) return false;
		$fstat = fstat($fd);
		if ($fstat['size'] != $hdr['fsize'])
		return false;
		$this->hash_base = $hdr['base'];
		$this->hash_prime = $hdr['prime'];
		$this->version = $hdr['ver'];
		$this->fsize = $hdr['fsize'];
		return true;
	}
	function _get_record($key)
	{
		$this->_io_times = 1;
		$index = ($this->hash_prime > 1 ? $this->_get_index($key) : 0);
		$poff = $index * 8 + 32;
		fseek($this->fd, $poff, SEEK_SET);
		$buf = fread($this->fd, 8);
		if (strlen($buf) == 8) $tmp = unpack('Ioff/Ilen', $buf);
		else $tmp = array('off' => 0, 'len' => 0);
		return $this->_tree_get_record($tmp['off'], $tmp['len'], $poff, $key);
	}
	function _tree_get_record($off, $len, $poff = 0, $key = '')
	{
		if ($len == 0)
		return (array('poff' => $poff));
		$this->_io_times++;
		fseek($this->fd, $off, SEEK_SET);
		$rlen = XDB_MAXKLEN + 17;
		if ($rlen > $len) $rlen = $len;
		$buf = fread($this->fd, $rlen);
		$rec = unpack('Iloff/Illen/Iroff/Irlen/Cklen', substr($buf, 0, 17));
		$fkey = substr($buf, 17, $rec['klen']);
		$cmp = ($key ? strcmp($key, $fkey) : 0);
		if ($cmp > 0)
		{
			unset($buf);
			return $this->_tree_get_record($rec['roff'], $rec['rlen'], $off + 8, $key);
		}
		else if ($cmp < 0)
		{
			unset($buf);
			return $this->_tree_get_record($rec['loff'], $rec['llen'], $off, $key);
		}
		else {
			$rec['poff'] = $poff;
			$rec['off'] = $off;
			$rec['len'] = $len;
			$rec['voff'] = $off + 17 + $rec['klen'];
			$rec['vlen'] = $len - 17 - $rec['klen'];
			$rec['key'] = $fkey;
			fseek($this->fd, $rec['voff'], SEEK_SET);
			$rec['value'] = fread($this->fd, $rec['vlen']);
			return $rec;
		}
	}
}