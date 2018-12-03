<?php

/* dispay functions for outputting information */

function partners_db_shortcode(){
    wp_register_style('slap_partners_db_css', '/wp-content/plugins/slap-partners-db/includes/css/slap-partners-db.css');
    wp_register_style('slap_bootstrap', 'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css');
    wp_enqueue_style('slap_partners_db_css');
    //wp_enqueue_style('slap_bootstrap');
    ob_start(); ?>

<script>
    function refresh(from){
        //document.rede_form.filter.value = '-1';
        if(from == "tipo"){
            document.rede_form.distrito.value = '-1';
            document.rede_form.concelho.value = '-1';
            document.getElementById("rede_form").submit();
        } else {
            document.getElementById("rede_form").submit();
        }
    }
</script>

<div class="container" id="partners_db_shortcode">
    <div class="row">
        <div class='rede_form_top' >
            <form role="search" name="rede_form" id='rede_form' method="GET" action="">
                <input type="hidden" name="filter" id="filter" value="1">
                <div class='center'>
                    <?php
                    if(isset($_GET['partner'])){
                        $lastName = $_GET['partner'];
                    } else {
                        $lastName = null;
                    }
                    ?>
                    
                    <input name="partner" id="partner" value="<?php //echo trim($lastName); ?>" placeholder="Nome" />
                    <div class='clear' style="height: 10px;"></div>
                    <select name="tipo" id="tipo" class="tipo-input" onchange="refresh('tipo')">
                        <option value='-1'>Todos os Tipo de Rede</option>
                    <?php
                        $lastTipo = $_GET["tipo"];
                        $args = array(
                            'taxonomy' => 'tipo',
                            'posts_per_page' => -1
                        );
    
                        $tipos = get_categories($args);
                        foreach($tipos as $tipo){
                            if($tipo->count > 0){
                                if($tipo->slug == $lastTipo) {$selected = "selected";} else {$selected = "";} ?>
                                <option value="<?php echo $tipo->slug; ?>" <?php echo $selected; ?> ><?php echo $tipo->name; ?></option>
                            <?php }
                        }
                    ?>
                    </select>
                    <div class='clear' style="height: 10px;"></div>
                    <select name='distrito' id='distrito' class="local-input" onchange="refresh()" >
                        <option value='-1'>Todos os Distritos</option>
                    <?php
                        $lastDistrito = $_GET['distrito'];
                        $args = array(
                            'post_type' => 'parceiro',
                            'posts_per_page' => -1
                        );
    
                        if( $_GET["tipo"] != "-1" ) {
                            $args['tax_query'][1]['taxonomy'] = 'tipo';
                            $args['tax_query'][1]['field']    = 'slug';
                            $args['tax_query'][1]['terms']    = $_GET['tipo'];
                        }
                        $loop = new WP_Query( $args );
                        while ( $loop->have_posts() ) : $loop->the_post();
                            $do_not_duplicate = $post->ID;
                            $distritos[] = get_post_meta(get_the_ID(), 'distrito', true);
                        endwhile;
                        $distritoss = array_unique($distritos);
                        foreach($distritoss as $distrito){
                            if($distrito == $lastDistrito) {$selected = "selected";} else {$selected = "";} ?>
                            <option value="<?php echo $distrito?>" <?php echo $selected; ?> ><?php echo $distrito?></option>
                        <?php }
                    ?>
                    </select>
                    
                    
                    <select name='concelho' id='concelho' class="local-input" style="float:right;margin-right:0px;" onchange="refresh()">
                        <?php
                        if (isset($_GET['distrito'])){
                            if($_GET['distrito'] == "-1"){?>
                                <option value='-1'>Selecione Distrito</option>
                            <?php
                            } else {
                        ?>
                        <option value='-1'>Todos os Concelhos</option>
                    <?php
                        $lastConcelho = $_GET['concelho'];
                        $args = array( 
                            'post_type' => 'parceiro',
                            'posts_per_page' => -1
                        );
                                
                        if( $_GET["tipo"] != "-1" ) {
                            $args['tax_query'][1]['taxonomy'] = 'tipo';
                            $args['tax_query'][1]['field']    = 'slug';
                            $args['tax_query'][1]['terms']    = $_GET['tipo'];
                            $args['meta_query'][1]['key'] = 'distrito';
                            $args['meta_query'][1]['value']    = $_GET["distrito"];
                            $args['meta_query'][1]['compare']    = 'LIKE';
                        }
                        $loop = new WP_Query( $args );
                        while ( $loop->have_posts() ) : $loop->the_post();
                            $do_not_duplicate = $post->ID;
                            $concelhos[] = get_post_meta(get_the_ID(), 'concelho', true);
                                
                        endwhile;
                        $concelhoss = array_unique($concelhos);
                        foreach($concelhoss as $concelho){
                            if($concelho == $lastConcelho) {$selected = "selected";} else {$selected = "";} ?>?>
                            <option value="<?php echo $concelho?>" <?php echo $selected; ?> ><?php echo $concelho?></option>
                        <?php } 
                            }
                        } else { ?>
                        <option value='-1'>Selecione Distrito</option>
                        <?php
                        }
                    ?>
                    </select>
                    
                    
                    <!--<select name='especialidade' id='especialidade' class='last' style="float:right;" onchange="refresh()">
                        <option value='-1'>Todas as Especialidade</option>
                    <?php
                        $last = $_GET["especialidade"];
                        $args = array(
                            'taxonomy' => 'especialidade',
                            'posts_per_page' => -1);
    
                        $especialidades = get_categories($args);
                        foreach($especialidades as $especialidade){
                            if($especialidade->count > 0){
                                if($especialidade->slug == $last) {$selected = "selected";} else {$selected = "";} ?>
                                <option value="<?php echo $especialidade->slug; ?>" <?php echo $selected; ?> ><?php echo $especialidade->name; ?></option>
                            <?php }
                        }
                    ?>
                    </select>-->
                    
                    
                    <div style="clear:both;"></div>
                </div>
                <button type="submit"></button>
            </form>
        </div>
    </div>


    <div class="row">
    <?php
    
//    $filterNome = $_GET['partner'];
//    $filterTipo = $_GET['tipo'];
//    $filterConcelho = $_GET['concelho'];
//    $filterDistrito = $_GET['distrito'];
//    $filterEspecialidade = "not_set";
//    $filtCat = get_term_by( 'slug', 'alergologia-pediatrica', 'Parceiro' );
//    if($filtCat->count > 0) {
//        $filterEspecialidade = $filtCat->name;
//    }
//    
    add_filter('posts_where', 'wpse18703_posts_where', 10, 2);
    
    function wpse18703_posts_where( $where, &$wp_query )
    {
        global $wpdb;
        $where .= ' AND ' . $wpdb->posts . '.post_title LIKE \'%' . esc_sql( $wpdb->esc_like( $_GET['partner'] ) ) . '%\'';
        return $where;
    }
    
    if($_GET["filter"] == '1'){
        $args = array( 
            'post_type' => 'parceiro',
            'posts_per_page' => -1
            );
        
        if( $_GET["tipo"] != "-1" ) {
            $args['tax_query'][1]['taxonomy'] = 'tipo';
            $args['tax_query'][1]['field']    = 'slug';
            $args['tax_query'][1]['terms']    = $_GET['tipo'];
        }
        
        if( $_GET["distrito"] != "-1" ) {
            $args['meta_query'][1]['key'] = 'distrito';
            $args['meta_query'][1]['value']    = $_GET["distrito"];
            $args['meta_query'][1]['compare']    = '=';
        }
        
        if( $_GET["concelho"] != "-1" ) {
            $args['meta_query'][1]['key'] = 'concelho';
            $args['meta_query'][1]['value']    = $_GET["concelho"];
            $args['meta_query'][1]['compare']    = '=';
        }
        
//        if( $_GET["especialidade"] != "-1" ) {
//            $args['tax_query'][1]['taxonomy'] = 'especialidade';
//            $args['tax_query'][1]['field']    = 'slug';
//            $args['tax_query'][1]['terms']    = $_GET['especialidade'];
//        }
        
        $loop = new WP_Query( $args );
        if ($loop->have_posts()){
            while ( $loop->have_posts() ) : $loop->the_post();
                $morada = get_post_meta(get_the_ID(), 'morada', true);
                $cp = get_post_meta(get_the_ID(), 'cp', true);
                $concelho = get_post_meta(get_the_ID(), 'concelho', true);
                $contactos = get_post_meta(get_the_ID(), 'contactos', true);
                $distrito = get_post_meta(get_the_ID(), 'distrito', true);
                $tipo = get_the_term_list(get_the_ID(), 'tipo' ,'', ', ','');
                $especialidades = get_the_term_list(get_the_ID(), 'especialidade' ,'', ', ','');
                ?>
                <div class="col-sm-6">
                    <h3 class="m10"><?php the_title(); ?></h3>
                    <!--<p><?php the_post_thumbnail( 'full' ); ?></p>-->
                    <!--<p class="description"><?php the_content(); ?></p>-->
                    <p class="m-10">
                        <?php echo "Morada: " . $morada . " - " . $cp; ?><br>
                        <?php echo "Concelho: " . $concelho ?><br>
                        <?php echo "Distrito: " . $distrito ?><br>
                        <?php echo "Contacto: " . $contactos ?><br>
                        <?php echo "Tipo de Rede: " . strip_tags($tipo) ?><br>
                        <?php //echo "Especialidades: " . strip_tags($especialidades) ?></p>
                </div>
                <?php
            endwhile;
        } else {?>
            <div class="cold-md-12">Não foram encontrados parceiros com os termos utilizados.<br>
<!--
                <ul>
            <?php
                echo $filterNome = ($filterNome != "") ? "<li>Nome: " . $filterNome . "</li>" : "" ;
                echo $filterTipo = ($filterTipo != "-1") ? "<li>Tipo de Rede: " . $filterTipo . "</li>" : "" ;
                echo $filterDistrito = ($filterDistrito != "-1") ? "<li>Distrito: " . $filterDistrito . "</li>" : "" ;
                echo $filterConcelho = ($filterConcelho != "-1") ? "<li>Concelho: " . $filterConcelho . "</li>" : "" ;
                echo $filterEspecialidade = ($filterEspecialidade != "") ? "<li>Especialidade: " . $filterEspecialidade . "</li>" : "" ;
                echo var_dump(filtCat);
            ?>
                </ul>
-->
            </div>
            <?php
        }
    } else { ?>
        <div class="cold-md-12">Selecione no directório acima a informação detalhada que pretende, escolhendo Nome, Tipo de Rede, Distrito, Concelho e/ou Especialidade e pesquise.</div>
        <?php 
    }
            ?>
    </div>
</div>

    <?php echo ob_get_clean();
}
add_shortcode('partners-db', 'partners_db_shortcode');