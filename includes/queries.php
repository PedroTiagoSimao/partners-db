<div class="container" id="partners_db_shortcode">
    <div class="row">
        <div class='rede_form_top' >
            <form id='rede_form' method="GET" action="">
                <input type="hidden" name="filter" value="1">
                <div class='center'>
                    <input name="name" id="name" value="" placeholder="Nome" />
                    <div class='clear' style="height: 10px;"></div>
                    <select name="tipo" id="tipo" onchange="this.form.submit()">
                        <option value='-1'>Tipo de Rede</option>
                    <?php
                        $selected = "";
                        $lastTipo = $_GET["tipo"];
                        $args = array(
                            'taxonomy' => 'tipo'  
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
                    <select name='distrito' id='distrito' style="float:right;margin-right:0px;" onchange="this.form.submit()" >
                        <option value='-1'>Distritos</option>
                    <?php
                        $lastDistrito = $_GET['distrito'];
                        $args = array( 'post_type' => 'parceiro' );
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
                    <div class='clear' style="height: 10px;"></div>
                    <select name='concelho' id='concelho' onchange="this.form.submit()" >
                        <option value='-1'>Concelho</option>
                    <?php
                        $lastConcelho = $_GET['concelho'];
                        $args = array( 'post_type' => 'parceiro' );
                        if( $_GET["tipo"] != "-1" ) {
                            $args['tax_query'][1]['taxonomy'] = 'tipo';
                            $args['tax_query'][1]['field']    = 'slug';
                            $args['tax_query'][1]['terms']    = $_GET['tipo'];
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
                    ?>
                    </select>
                    <select name='especialidade' id='especialidade' class='last' style="float:right;">
                        <option value='-1'>Especialidade</option>
                    <?php
                        $last = $_GET["especialidade"];
                        $args = array(
                            'taxonomy' => 'especialidade');
    
                        $especialidades = get_categories($args);
                        foreach($especialidades as $especialidade){
                            if($especialidade->count > 0){
                                if($especialidade->slug == $last) {$selected = "selected";} else {$selected = "";} ?>
                                <option value="<?php echo $especialidade->slug; ?>" <?php echo $selected; ?> ><?php echo $especialidade->name; ?></option>
                            <?php }
                        }
                    ?>
                    </select>
                    <div style="clear:both;"></div>
                </div>
                <button type="submit"></button>
            </form>
        </div>
    </div>


    <div class="row">
    <?php
    
    function title_filter( $where, &$wp_query ){
        global $wpdb;
        if ( $search_term = $wp_query->get( 'post_title' ) ) {
            $where .= ' AND ' . $wpdb->posts . '.post_title LIKE \'%' . esc_sql( like_escape( $_GET['name'] ) ) . '%\'';
        }
        return $where;
    }
    
    $filter = $_GET["filter"];
    if(isset($filter)){
        $args = array( 
            'post_type' => 'parceiro',
            /*'meta_query' => array(
                array(
                    'key' => 'distrito',
                    'value' => 'Leiria',
                    'compare' => 'LIKE'
                )
            )
            'posts_per_page' => 10*/ );
        
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
        
        if( $_GET["especialidade"] != "-1" ) {
            $args['tax_query'][1]['taxonomy'] = 'especialidade';
            $args['tax_query'][1]['field']    = 'slug';
            $args['tax_query'][1]['terms']    = $_GET['especialidade'];
        }
        
        $loop = new WP_Query( $args );
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
                <h3><?php the_title(); ?></h3>
                <p><?php the_post_thumbnail( 'full' ); ?></p>
                <p class="description"><?php the_content(); ?></p>
                <p><?php echo "Contactos: " . $contactos ?><br>
                    <?php echo "Morada: " . $morada . " - " . $cp; ?><br>
                    <?php echo "Concelho: " . $concelho ?><br>
                    <?php echo "Distrito: " . $distrito ?><br>
                    <?php echo "Tipo de Rede: " . $tipo ?><br>
                    <?php echo "Especialidades: " . $especialidades ?></p>
            </div>
            <?php
        endwhile;
    } else { ?>
                <div class="cold-md-12">Selecione no directório acima a informação detalhada que pretende, escolhendo Nome, Tipo de Rede, Distrito, Concelho e/ou Especialidade e pesquise.</div>
            <?php 
    }
            ?>
        </div>
    </div>