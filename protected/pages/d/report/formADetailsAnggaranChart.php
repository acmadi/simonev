<?php
prado::using('Application.ChartPage');
prado::using ('Application.lib.jpgraph.jpgraph_line');
class formADetailsAnggaranChart extends ChartPage {    
    public function onLoad ($param) {		
		parent::onLoad($param);	
        $this->createObjKegiatan();        
        $this->generateImage();        
    }    
    private function generateImage () {
        $bulan=$this->TGL->getMonth(3);
        $iduraian=$this->session['currentPageFormADetails']['iduraian'];
        $realisasi=$this->kegiatan->getList("penggunaan WHERE iduraian=$iduraian",array('bulan','realisasi'),'bulan',null,5);                
        $points=array();
        $nilai=0;
        foreach ($bulan as $k=>$v) {            
            if (isset($realisasi[$k])) {
                $nilai+=$realisasi[$k];
            }
            $points[]=($nilai > 0)?$nilai/1000000:0;            
        }
        // Size of the overall graph
        $width=800;
        $height=300;

        // Create the graph and set a scale.
        // These two calls are always required
        $graph = new Graph($width,$height);
        $graph->SetScale('textlin');
        $graph->SetFrame(false);
        $graph->SetMargin(100,20,60,20);
        $graph->title->Set ('Realisasi Anggaran (dalam juta)');
        $graph->title->SetFont (FF_FONT1,FS_BOLD);
        $graph->SetShadow();        

        $graph->xaxis->title->SetFont (FF_FONT1,FS_BOLD);
        $graph->xaxis->setTitleMargin(13);
        $graph->xaxis->SetTickLabels(array('Jan','Feb','Mar','Apr','Mei','Jun','Jul','Aug','Sep','Okt','Nov','Des'));

        $graph->yaxis->title->SetFont (FF_FONT1,FS_BOLD);
        $graph->yaxis->setTitleMargin(80);
        $graph->yaxis->SetColor('blue');      
        if ($nilai > 1000000) $graph->yaxis->SetLabelFormatCallback(array('FormattingGraph','toRupiah'));
        
        // Create the linear plot
        $lineplot = new LinePlot($points);
        $lineplot->setColor('blue');
        $lineplot->SetWeight(2);        
        $lineplot->mark->SetType (MARK_STAR);
        $lineplot->mark->SetColor ('red');
        $lineplot->value->setMargin(14);
        $lineplot->SetCenter();
        // Add the plot to the graph
        $graph->Add($lineplot);
        // Display the graph
        $graph->Stroke();
    }
}
?>
