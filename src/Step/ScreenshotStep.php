<?php

namespace Screenshot\Step;

use Screenshot\Shooter;

class ScreenshotStep implements StepInterface
{
    private $name;
    private $cropid;
    private $cropmargin = 20;
    private $highlightid;
    
    public function getName()
    {
        return $this->name;
    }
    
    public function setName($name)
    {
        $this->name = $name;
    }
    
    public function setCropId($id)
    {
        $this->cropid = $id;
    }
    
    public function setHighlightId($id)
    {
        $this->highlightid = $id;
    }
    
    public function execute(Shooter $shooter)
    {
        $shooter->addInfo("Taking screenshot: " . $this->name);
        
        $session = $shooter->getSession();
        $screenshotdata = $session->getDriver()->getScreenshot();
        
        $image = imagecreatefromstring($screenshotdata);
        imageantialias($image, true);
        $size = getimagesizefromstring($screenshotdata);
        //$image = imagescale($image, $shooter->getWidth());
        
        $image_p = imagecreatetruecolor($shooter->getWidth(), $shooter->getHeight());
        imagecopyresampled($image_p, $image, 0, 0, 0, 0, $shooter->getWidth(), $shooter->getHeight(), $size[0], $size[1]);
        
        $image = $image_p;
        imageantialias($image, true);
        
        print_r($size);
        
        if ($this->highlightid) {
            echo "Fetching dimensions for element id " . $this->highlightid . "\n";
            $box = $session->evaluateScript(
                "return document.getElementById('"  . $this->highlightid . "').getBoundingClientRect();"
            );
            var_dump($box);
            
            $color = imagecolorallocate($image, 255, 0, 0);
            for ($i=0; $i<4; $i++) {
                /*
                imagerectangle(
                    $image,
                    $box['left'] - $i,
                    $box['top'] - $i,
                    $box['left'] + $box['width'] + ($i),
                    $box['top'] + $box['height'] + ($i),
                    $color
                );
                */
                $x = $box['left'] + ($box['width'] / 2);
                $y = $box['top'] + ($box['height'] / 2);
                //imageellipse($image, $x, $y, $box['width']+$i, $box['height']+$i, $color);
                $this->imageEllipseAA($image, $x, $y, $box['width']+$i, $box['height']+$i, $color);
            }

            
        }
        
        if ($this->cropid) {
            // evaluate JS expression:
            echo "Fetching dimensions for element id " . $this->cropid . "\n";
            $box = $session->evaluateScript(
                "return document.getElementById('"  . $this->cropid . "').getBoundingClientRect();"
            );
            var_dump($box);
            $width = $box['width'] + ($this->cropmargin * 2);
            if ($width > $shooter->getWidth() - $box['left'] + $this->cropmargin) {
                $width = $shooter->getWidth() - $box['left'] + $this->cropmargin;
            }

            $height = $box['height'] + ($this->cropmargin * 2);
            if ($height > $shooter->getHeight() - $box['top'] + $this->cropmargin) {
                $height = $shooter->getHeight() - $box['top'] + $this->cropmargin;
            }
            $rect = array(
                'x' => $box['left'] - $this->cropmargin,
                'y' => $box['top'] - $this->cropmargin,
                'width' => $width,
                'height'=> $height
            );
            $image = imagecrop($image, $rect);

            $image_p = imagecreatetruecolor($shooter->getWidth(), $shooter->getHeight());
            imagecopyresampled($image_p, $image, 0, 0, 0, 0, $shooter->getWidth(), $shooter->getHeight(), $width, $height);

        }
        $tmpfilename = '/tmp/tmp-' . $this->name .'.png';
        imagepng($image, $tmpfilename, null);
        imagedestroy($image);

        $storageservice = $shooter->getStorageService();
        $storageservice->upload($this->name . '.png', $tmpfilename);
        unlink($tmpfilename);
    }
    
    private function imageEllipseAA( &$img, $x, $y, $w, $h,$color,$segments=180) 
    {
        $w=$w/2;
        $h=$h/2;
        $jump=2*M_PI/$segments;
        $oldx=$x+sin(-$jump)*$w;
        $oldy=$y+cos(-$jump)*$h;
        for($i=0;$i<2*(M_PI);$i+=$jump)
        {
            $newx=$x+sin($i)*$w;
            $newy=$y+cos($i)*$h;
            ImageLine($img,$newx,$newy,$oldx,$oldy,$color);
            $oldx=$newx;
            $oldy=$newy;
        }
    }
}
