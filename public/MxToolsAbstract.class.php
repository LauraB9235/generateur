<?php
   namespace Addon\test2;

   class MxToolsAbstract{

   protected $templateFile;
   protected $templatePath;
   protected $mx;
   protected $allowModelesCascades = false;

   function __construct()
   {
     $this->initMx();
   }

   public function initMx(){
     $this->mx = new \Modelixe($this->templateFile);

     $this->mx->SetMxTemplatePath( $this->getTemplatePath() . $this->templatePath);
     $this->mx->SetModeliXe("mx");
   }

   protected function getSkin(){
      if( !$this->skin){
      $skin = \em_misc::getSkin();
      $skin = $skin["root"]["Skin"]["config"]["name"];


      if( $this->allowModelesCascades){
        $i = 1;

         while(
            isset($this->templatesFile)
            && !file_exists( \em_misc::getSpecifPath() . "../modeles/" . \em_misc::getLang() . "/" . $skin . "/" .$this->templatePath . "/". $this->templateFile )
            && $i<=count( $GLOBALS['siteconfig']['RUBRIQUE']['SKIN'] )
            ){
            $skin = $GLOBALS['siteconfig']['RUBRIQUE']['.SKIN.']['skin'.$i];
            $i++;
         }
     }
         return $skin;
    }
    else{
         return $this->skin;
    }
   }

   public function getTemplatePath(){
     return \em_misc::getSpecifPath() . "../modeles/" . \em_misc::getLang() . "/" . $this->getSkin() . "/";
   }
   public function setSkin($_skin){
     $this->skin = $_skin;
   }

   public function getStringContent()
   {
    if($this->mx)
      return $this->mx->mxWrite();
    return "";
   }

   public function deleteBlock($block)
   {
     \em_mx::delete( $this->mx, $block);
   }

   public function getBlock($block)
   {
     return \em_mx::get( $this->mx, $block);
   }
   public function loopBlock($block)
   {
     return \em_mx::loop( $this->mx, $block);
   }
   public function mxText( $block, $content)
   {
     $this->mx->mxText( $block, $content);
   }
   public function mxAttribut( $block, $content)
   {
     $this->mx->mxAttribut($block, $content);
   }

   public function start(){}

   public function setTemplateFile($file)
   {
     $this->templateFile = $file;
   }
   public function setTemplatePath($path)
   {
     $this->templatePath = $path;
   }
   public function getMx()
   {
     return $this->mx;
   }
   public function setAllowModelesCascade($boolean)
   {
     $this->allowModelesCascade = $boolean;
   }
 }
