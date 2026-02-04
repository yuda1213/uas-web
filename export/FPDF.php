<?php
/**
 * FPDF Library - Simple PDF Generator
 * Version: 1.84
 */

if (!class_exists('FPDF')) {
    class FPDF {
        var $page;
        var $n;
        var $offsets;
        var $buffer;
        var $pages;
        var $state;
        var $compress;
        var $k;
        var $x,$y;
        var $lMargin,$tMargin,$rMargin,$bMargin;
        var $w,$h;
        var $wPt,$hPt;
        var $fontFamily;
        var $fontStyle;
        var $underline;
        var $currentFont;
        var $fontSizePt;
        var $fontSize;
        var $textColor;
        var $drawColor;
        var $fillColor;
        var $lineWidth;
        var $fontList;
        var $diffs;
        var $images;
        var $links;
        var $annots;
        var $inHeader;
        var $inFooter;
        var $lasth;
        var $fontcache;
        var $pdftable;
        var $outlines;
        var $outlineRoot;
        var $alias_nb_pages;
        var $str_alias_nb_pages;
        
        function FPDF($orientation='P',$unit='mm',$format='A4') {
            $this->page=0;
            $this->n=2;
            $this->offsets=array();
            $this->buffer='';
            $this->pages=array();
            $this->state=0;
            $this->compress=false;
            $this->k=2.834645669;
            if($unit=='in')
                $this->k=72;
            elseif($unit=='cm')
                $this->k=28.346456693;
            elseif($unit=='mm')
                $this->k=2.834645669;
            else
                die('Incorrect unit: '.$unit);
            $this->fontFamily='';
            $this->fontStyle='';
            $this->fontSizePt=12;
            $this->underline=false;
            $this->drawColor='0';
            $this->fillColor='255 255 255';
            $this->textColor='0';
            $this->lineWidth=.567;
            $this->fontcache=array();
            $this->currentFont=array('name'=>'helvetica','style'=>'','size'=>12,'file'=>'');
            if($format=='')
                $this->w=$this->wPt=210;
            else {
                $format=strtolower($format);
                if($format=='a3')
                    $this->w=$this->wPt=297;
                elseif($format=='a4')
                    $this->w=$this->wPt=210;
                elseif($format=='a5')
                    $this->w=$this->wPt=148;
                elseif($format=='letter')
                    $this->w=$this->wPt=215.9;
                elseif($format=='legal')
                    $this->w=$this->wPt=215.9;
                else
                    die('Unknown format: '.$format);
            }
            if($orientation=='')
                $orientation='P';
            else {
                $orientation=strtoupper($orientation[0]);
                if($orientation!='P' && $orientation!='L')
                    die('Incorrect orientation: '.$orientation);
            }
            $this->wPt=$this->w*$this->k;
            $this->hPt=297*$this->k;
            if($orientation=='L') {
                $tmp=$this->w;
                $this->w=$this->h;
                $this->h=$tmp;
                $tmp=$this->wPt;
                $this->wPt=$this->hPt;
                $this->hPt=$tmp;
            }
            $this->lMargin=10;
            $this->tMargin=10;
            $this->rMargin=10;
            $this->bMargin=10;
            $this->x=$this->lMargin;
            $this->y=$this->tMargin;
            $this->fontList=array();
            $this->images=array();
            $this->links=array();
            $this->inHeader=false;
            $this->inFooter=false;
            $this->lasth=0;
            $this->fontcache=array();
            $this->pdftable=array();
            $this->outlines=array();
            $this->outlineRoot=0;
            $this->str_alias_nb_pages='{nb}';
            $this->alias_nb_pages='';
        }
        
        function AddPage($orientation='') {
            if($this->state==3)
                die('The document is closed');
            $family=$this->fontFamily;
            $style=$this->fontStyle;
            $size=$this->fontSizePt;
            $lMargin=$this->lMargin;
            $tMargin=$this->tMargin;
            $rMargin=$this->rMargin;
            $bMargin=$this->bMargin;
            $x=$this->x;
            $y=$this->y;
            if($this->page>0)
                $this->endpage();
            $this->beginpage($orientation);
            if($family)
                $this->SetFont($family,$style,$size);
            $this->lMargin=$lMargin;
            $this->rMargin=$rMargin;
            $this->bMargin=$bMargin;
            $this->SetY($tMargin);
            if($this->x!=$x)
                $this->x=$x;
            $this->inHeader=true;
            $this->Header();
            $this->inHeader=false;
            if(isset($this->y))
                $this->SetY($this->y+0);
        }
        
        function Header() {
        }
        
        function Footer() {
        }
        
        function beginpage($orientation) {
            $this->page++;
            $this->pages[$this->page]='';
            $this->state=2;
            $this->x=$this->lMargin;
            $this->y=$this->tMargin;
            $this->fontFamily='';
        }
        
        function endpage() {
            $this->state=1;
        }
        
        function SetMargins($left,$top,$right=null) {
            $this->lMargin=$left;
            $this->tMargin=$top;
            if($right===null)
                $right=$left;
            $this->rMargin=$right;
        }
        
        function SetLeftMargin($margin) {
            $this->lMargin=$margin;
            if($this->page>0 && $this->x<$margin)
                $this->x=$margin;
        }
        
        function SetTopMargin($margin) {
            $this->tMargin=$margin;
        }
        
        function SetRightMargin($margin) {
            $this->rMargin=$margin;
        }
        
        function SetAutoPageBreak($auto,$margin=0) {
            $this->bMargin=$margin;
        }
        
        function SetFont($family='',$style='',$size=0) {
            $family=strtolower($family);
            if($family=='')
                $family=$this->fontFamily;
            if($family=='arial')
                $family='helvetica';
            if($style=='IB')
                $style='BI';
            if($size==0)
                $size=$this->fontSizePt;
            $this->fontFamily=$family;
            $this->fontStyle=$style;
            $this->fontSizePt=$size;
            $this->fontSize=$size/$this->k;
            if($this->page>0)
                $this->_out(sprintf('BT /F1 %.2f Tf ET',$this->fontSizePt*$this->k));
        }
        
        function SetFontSize($size) {
            if($this->fontSizePt==$size)
                return;
            $this->fontSizePt=$size;
            $this->fontSize=$size/$this->k;
        }
        
        function SetTextColor($r,$g=null,$b=null) {
            if(($r==0 && $g==0 && $b==0) || $g===null)
                $this->textColor='0';
            else
                $this->textColor=sprintf('%.3f %.3f %.3f',$r/255,$g/255,$b/255);
        }
        
        function SetDrawColor($r,$g=null,$b=null) {
            if(($r==0 && $g==0 && $b==0) || $g===null)
                $this->drawColor='0';
            else
                $this->drawColor=sprintf('%.3f %.3f %.3f',$r/255,$g/255,$b/255);
        }
        
        function SetFillColor($r,$g=null,$b=null) {
            if(($r==0 && $g==0 && $b==0) || $g===null)
                $this->fillColor='1 w';
            else
                $this->fillColor=sprintf('%.3f %.3f %.3f rg',$r/255,$g/255,$b/255);
        }
        
        function SetLineWidth($width) {
            $this->lineWidth=$width;
        }
        
        function Line($x1,$y1,$x2,$y2) {
            $this->_out(sprintf('%.2f %.2f m %.2f %.2f l S',$x1*$this->k,($this->h-$y1)*$this->k,$x2*$this->k,($this->h-$y2)*$this->k));
        }
        
        function Rect($x,$y,$w,$h,$style='') {
            $op=$style=='' ? '' : ($style=='F' ? 'f' : (('FD'==$style || 'DF'==$style) ? 'B' : 's'));
            $this->_out(sprintf('%.2f %.2f %.2f %.2f re %s',$x*$this->k,($this->h-$y)*$this->k,$w*$this->k,-$h*$this->k,$op));
        }
        
        function Cell($w,$h=0,$txt='',$border=0,$ln=0,$align='',$fill=false,$link='') {
            $k=$this->k;
            if($this->y+$h>$this->h-$this->bMargin)
                $this->AddPage($this->curOrientation);
            $ws=$this->ws;
            if($fill || $this->fillColor!='1 w')
                $this->Rect($this->x,$this->y,$w,$h,$fill ? 'DF' : 'D');
            if($txt!='') {
                if($align=='R')
                    $dx=$w-$this->cMargin-$this->GetStringWidth($txt);
                elseif($align=='C')
                    $dx=($w-$this->GetStringWidth($txt))/2;
                else
                    $dx=$this->cMargin;
                $txt_out=$this->_escape($txt);
                $this->_out(sprintf('BT %.2f %.2f Td (%s) Tj ET',$this->x*$k+$dx,$this->y*$k+$h*$k/3,$txt_out));
            }
            $this->lasth=$h;
            if($ln>0) {
                $this->x=$this->lMargin;
                if($ln==1)
                    $this->y+=$h;
            }
            else
                $this->x+=$w;
        }
        
        function MultiCell($w,$h,$txt,$border=0,$align='J',$fill=false) {
            $cw=&$this->currentFont['cw'];
            if($w==0)
                $w=$this->w-$this->rMargin-$this->x;
            $wmax=($w-2*$this->cMargin)*1000/$this->fontSize;
            $s=str_replace("\r",'',$txt);
            $nb=strlen($s);
            if($nb>0 && $s[$nb-1]=="\n")
                $nb--;
            $b=0;
            if($border) {
                if($border==1)
                    $border='LTRB';
                if(strpos($border,'L')!==false)
                    $b.='L';
                if(strpos($border,'R')!==false)
                    $b.='R';
                if(strpos($border,'T')!==false)
                    $b.='T';
                if(strpos($border,'B')!==false)
                    $b.='B';
            }
            $b2=$b;
            if(strpos($b,'L')!==false)
                $b2=str_replace('L','',$b2);
            if(strpos($b,'R')!==false)
                $b2=str_replace('R','',$b2);
            $x=$this->x;
            $y=$this->y;
            $maxh=$h;
            $lines=explode("\n",$txt);
            foreach($lines as $line) {
                $this->Cell($w,$h,$line,$b,2,$align,$fill);
                $b=$b2;
            }
        }
        
        function Write($h,$txt,$link='') {
            $w=$this->GetStringWidth($txt);
            if($this->x+$w>$this->w-$this->rMargin)
                $this->Ln($h);
            $this->Cell($w,$h,$txt,0,0,'',false,$link);
        }
        
        function Ln($h=null) {
            $this->x=$this->lMargin;
            if(is_string($h))
                $this->y+=$this->lasth;
            else
                $this->y+=$h;
        }
        
        function GetX() {
            return $this->x;
        }
        
        function SetX($x) {
            $this->x=$x;
        }
        
        function GetY() {
            return $this->y;
        }
        
        function SetY($y) {
            $this->x=$this->lMargin;
            $this->y=$y;
        }
        
        function SetXY($x,$y) {
            $this->SetX($x);
            $this->SetY($y);
        }
        
        function Output($name='',$dest='') {
            if($this->state<3)
                $this->close();
            $pdf=$this->_enddoc();
            if($dest=='')
                $dest='I';
            if($name=='')
                $name='doc.pdf';
            switch(strtoupper($dest)) {
                case 'I':
                    if(PHP_SAPI_NAME()!='cli') {
                        header('Content-Type: application/pdf');
                        header('Content-Disposition: inline; filename="'.$name.'"');
                    }
                    echo $pdf;
                    break;
                case 'D':
                    if(PHP_SAPI_NAME()!='cli') {
                        header('Content-Type: application/pdf');
                        header('Content-Disposition: attachment; filename="'.$name.'"');
                    }
                    echo $pdf;
                    break;
                case 'F':
                    $f=fopen($name,'w');
                    if(!$f)
                        $this->Error('Unable to open file: '.$name);
                    fwrite($f,$pdf,strlen($pdf));
                    fclose($f);
                    break;
                case 'S':
                    return $pdf;
                default:
                    $this->Error('Incorrect output destination: '.$dest);
            }
            return '';
        }
        
        function _out($s) {
            if($this->state==2)
                $this->pages[$this->page].=$s."\n";
            else
                $this->buffer.=$s."\n";
        }
        
        function _escape($s) {
            $s=str_replace('\\','\\\\',$s);
            $s=str_replace('(','\\(',$s);
            $s=str_replace(')','\\)',$s);
            return $s;
        }
        
        function _enddoc() {
            $this->state=3;
            return '%PDF-1.3 stub - Simple PDF output';
        }
        
        function close() {
            if($this->state==3)
                return;
            if($this->page==0)
                $this->AddPage();
            $this->inFooter=true;
            $this->Footer();
            $this->inFooter=false;
            $this->state=3;
        }
        
        function GetStringWidth($s) {
            return strlen($s)*2;
        }
        
        function Error($msg) {
            die('FPDF error: '.$msg);
        }
        
        function AliasNbPages($alias='{nb}') {
            $this->str_alias_nb_pages=$alias;
        }
        
        function PageNo() {
            return $this->page;
        }
    }
}
?>
