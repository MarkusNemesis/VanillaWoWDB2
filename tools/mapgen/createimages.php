<pre>
<?php
/*
    generate_maps1.php - code for extracting regular maps for AoWoW
    This file is a part of AoWoW project.
    Copyright (C) 2010  Mix <ru-mangos.ru>

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU Affero General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU Affero General Public License for more details.

    You should have received a copy of the GNU Affero General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/
  require("config.php");

  if (!isset($config["mpq"]))
    die("Path to extracted MPQ files is not configured");
  if (!isset($config["maps"]))
    die("Path where to extract maps is not configured");

  $mpqdir = $config["mpq"];
  $outmapdir = $config["maps"];
  if (isset($config["tmp"]))
  {
    $outtmpdir = $config["tmp"];
    @mkdir($outtmpdir);
  }

  $dbcdir = $mpqdir . "DBFilesClient/";
  if (@stat($dbcdir) == NULL)
    $dbcdir = $mpqdir . "dbfilesclient/";

  $worldmapdir = $mpqdir . "interface/worldmap/";
  $normaldir = $outmapdir . "enus/normal/";
  $zoomdir   = $outmapdir . "enus/zoom/";
  // Maps are in 3:2 aspect ratio, so any resolution you set below must match this ratio.
  $blpmapwidth = 1002;
  $blpmapheight = 668;

  @mkdir($normaldir, 0777, true);
  @mkdir($zoomdir, 0777, true);

  require("dbc2array.php");
  require("imagecreatefromblp.php");

  function dbc2array_($filename, $format)
  {
    global $dbcdir;
    if (@stat($dbcdir . $filename) == NULL) $filename = strtolower($filename);
    return dbc2array($dbcdir . $filename, $format);
  }

  function status($message)
  {
    echo $message;
    @ob_flush();
    flush();
    @ob_end_flush();
  }

  status("Reading subzones list...");
  $dbc = dbc2array_("WorldMapOverlay.dbc", "nixxxxxxsiiiixxxx");
  $wmo = array();
  foreach ($dbc as $row)
    if ($row[2])
      $wmo[$row[1]][] = array
      (
        "name"   => strtolower($row[2]),
        "width"  => $row[3],
        "height" => $row[4],
        "left"   => $row[5],
        "top"    => $row[6]
      );
  status(count($dbc) . "\n");

  status("Reading zones list...");
  $dbc = dbc2array_("WorldMapArea.dbc", "nxisxxxx");
  status(count($dbc) . "\n");

  $count = 0;
  foreach ($dbc as $row)
  {
    $count++;
    if ($row[1])
    {
      $zid = $row[0];
      $mapid = $row[1];
      $mapname = $row[2];
      status($mapname . "[" . $mapid . "]");
      $mapname = strtolower($mapname);

      $map = imagecreatetruecolor(1024, 768);

      $mapfg = imagecreatetruecolor(1024, 768);
      imagesavealpha($mapfg, true);
      imagealphablending($mapfg, false);
      $bgcolor = imagecolorallocatealpha($mapfg, 0, 0, 0, 127);
      imagefilledrectangle($mapfg, 0, 0, 1023, 767, $bgcolor);
      imagecolordeallocate($mapfg, $bgcolor);
      imagealphablending($mapfg, true);
      echo ".";

      $prefix = $worldmapdir . $mapname . "/" . $mapname;
      if (@stat($prefix . "1.blp") == NULL)
        $prefix = $prefix . "1_";
      if (@stat($prefix . "1.blp") == NULL)
      {
        status(" not found.\n");
        continue;
      }
      for ($i = 0; $i < 12; $i++)
      {
        $img = imagecreatefromblp($prefix . ($i+1) . ".blp");
        imagecopyresampled($map, $img, 256*($i%4), 256*intval($i/4), 0, 0, 256, 256, imagesx($img), imagesy($img));
        imagedestroy($img);
      }
      echo ".";

      if (isset($wmo[$zid]))
      {
        foreach ($wmo[$zid] as $row)
        {
          $i = 1; $y = 0;
          while($y < $row["height"])
          {
            $x = 0;
            while($x < $row["width"])
            {
              $img = imagecreatefromblp($worldmapdir . $mapname . "/" . $row["name"] . $i . ".blp");
              imagecopy($mapfg, $img, $row["left"]+$x, $row["top"]+$y, 0, 0, imagesx($img), imagesy($img));
              imagedestroy($img);
              $x += 256;
              $i++;
            }
            $y += 256;
          }
        }
        echo ".";

        if (isset($outtmpdir))
        {
          $tmp = imagecreate(1000,1000);
          $cbg = imagecolorallocate($tmp, 255,255,255);
          $cfg = imagecolorallocate($tmp, 0,0,0);
          for ($y = 0; $y < 1000; $y++)
            for ($x = 0; $x < 1000; $x++)
            {
              $a = imagecolorat($mapfg, ($x*$blpmapwidth)/1000, ($y*$blpmapheight)/1000)>>24;
              imagesetpixel($tmp, $x, $y, $a < 30 ? $cfg : $cbg);
            }
          imagepng($tmp, $outtmpdir . $mapid . ".png");
          imagecolordeallocate($tmp, $cbg);
          imagecolordeallocate($tmp, $cfg);
          imagedestroy($tmp);
          echo ".";
        }
      }

      //imagepng($mapfg, $mapid . "_fg.png");
      //imagejpeg($map, $mapid . ".jpg");
      //imagepng($map, $mapid . ".png");
      for ($y = 0; $y < imagesy($mapfg); $y++)
        for ($x = 0; $x < imagesx($mapfg); $x++)
        {
          $c = imagecolorat($mapfg, $x, $y);
          if (($c>>24) < 127 && ($c>>24) > 0)
          {
            $c &= 0xFFFFFF;
            imagesetpixel($mapfg, $x, $y, $c);
          }
        }
      imagecopy($map, $mapfg, 0, 0, 0, 0, imagesx($mapfg), imagesy($mapfg));
      imagedestroy($mapfg);

      // Change the sizes here for the output size. e.g. 1280,853 and 1440,960
      $imgnew = imagecreatetruecolor(1280,853);
      imagecopyresampled($imgnew, $map, 0,0, 0,0, 1280,853, $blpmapwidth,$blpmapheight);
      imagepng($imgnew, $normaldir . $mapid . ".png");
      imagejpeg($imgnew, $normaldir . $mapid . ".jpg");
      imagedestroy($imgnew);
      $imgnew = imagecreatetruecolor(1440,960);
      imagecopyresampled($imgnew, $map, 0,0, 0,0, 1440,960, $blpmapwidth,$blpmapheight);
      imagepng($imgnew, $zoomdir . $mapid . ".png");
      imagejpeg($imgnew, $zoomdir . $mapid . ".jpg");
      imagedestroy($imgnew);

      imagedestroy($map);

      status("done (" . intval($count*100/count($dbc)) . "%)\n");
    }
  }

?>
</pre>

