<?php
?>
                <aside class="sidebar">
                    <div class="sidebar-container">
                        <div class="sidebar-header">
                            <div class="brand">
                                <div class="logo"> <img src="images/logo_claro_med.png" tilte="Cpanel Home" alt="Cpanel" /> </div> Cpanel</div>
                            </div>
                      <?php if( ($_SESSION["login"]["status"] == "active") && $permissions >2 ){?>
                        <nav class="menu">
                            <ul class="nav metismenu" id="sidebar-menu">
                               <?php if($permissions==10){?>
                                <li>
                                <a href="/<?php echo BASE_PATH;?>/"> <i class="fa fa-home"></i> <?php printf(_("Detalles"));?></a>
                                </li>
                                <li>
                                <a href=""> <i class="fa fa-th-large"></i><?php printf(_("Aplicaciones"));?><i class="fa arrow"></i> </a>
                                    <ul>
                                    <li> <a href="/<?php echo BASE_PATH;?>/services.php">
                                          <?php printf(_("Instaladas"));?>
                                                        </a> </li>
                                    <li> <a href="/<?php echo BASE_PATH;?>/service-available.php">
                                          <?php printf(_("Disponibles"));?>
                                                        </a> </li>
                                    </ul>
                                </li>
                                <li>
                                <a href=""> <i class="fa fa-globe"></i><?php printf (_("Dominios"));?> <i class="fa arrow"></i> </a>
                                <ul>
                                    <li><a href="/<?php echo BASE_PATH;?>/view-domains.php"><?php printf(_("Ver Dominios"));?></a></li>
                                    <li><a href="/<?php echo BASE_PATH;?>/add-domain.php"><?php printf(_("Añadir Dominio"));?></a></li>
                                    <li><a href="/<?php echo BASE_PATH;?>/domain-instruccions.php"><?php printf(_("Instrucciones"));?></a></li>
                                </ul>
                                </li>
                                <?php }?>
                                <?php if(!empty($serv_installed) && array_search('mail', array_column(array_column($serv_installed, 'ou'),0)) !== false){?>
                                <li>
                                <a href=""> <i class="fa fa-envelope-o"></i> <?php printf (_("Email"));?><i class="fa arrow"></i> </a>
                                    <ul>
                                     <li><a href="/<?php echo BASE_PATH;?>/mails.php"><?php printf(_("Cuentas mail"));?></a></li>
                                     <?php if( !empty($serv_installed) &&  array_search('rainloop', array_column(array_column($serv_installed, 'ou'),0)) !== false){?>
                                      <li><a href="https://<?php echo $_SERVER['HTTP_HOST'];?>/rainloop" target="_blank"><?php printf(_("Webmail"));?></a></li>
                                     <?php } ?>

                                    </ul>
                                </li>
                                <?php } ?>
                                <?php if ($permissions >= 10) {?>
                                  <li><a href="/<?php echo BASE_PATH;?>/usuarios.php"><i class="fa fa-users"></i> <?php printf(_("Usuarios"));?></a></li>
                                  <li><a href="/<?php echo BASE_PATH;?>/notificaciones.php"><i class="fa fa-mail-forward"></i><?php printf(_("Notificaciones"));?></a></li>
                                <?php } ?>
                              <?php if( !empty($serv_installed) && array_search('owncloud', array_column(array_column($serv_installed, 'ou'),0)) !== false){?>

                                <li>
                                  <a target="_blank" href="https://<?php echo $_SERVER['HTTP_HOST'];?>/owncloud"><i class="fa fa-cloud"></i> <?php printf(_("Owncloud"));?></a>
                                </li>
                              <?php }?>
                             <?php if( !empty($serv_installed) && array_search('phpmyadmin', array_column(array_column($serv_installed, 'ou'),0)) !== false){?>
                              <li>
                                <a href="#" data-toggle="dropdown" class="dropdown-toggle">Mysql <i class="fa arrow"></i> </a>
                                <ul>
                                  <li><a href="/<?php echo BASE_PATH;?>/mysql.php"><?php printf(_("Instrucciones"));?></a></li>
                                  <li><a target="_blank" href="https://<?php echo $_SERVER['HTTP_HOST'];?>/phpmyadmin"><?php printf(_("Phpmyadmin"));?></a></li>
                                </ul>
                              </li>
                              <?php }?>

                                <li>
                                    <a href=""> <i class="fa fa-book"></i><?php printf(_("Documentación"));?><i class="fa arrow"></i> </a>
                                    <ul>
                                      <li><a href="https://owncloud.org/" target="_blank"><?php printf(_("Owncloud"));?></a></li>
                                      <li><a href="http://docs.maadix.net/dominios/" target="_blank"><?php printf(_("Dominios"));?></a></li>
                                      <li><a href="http://docs.maadix.net/email" target="_blank"><?php printf(_("Email"));?></a></li>
                                      <li><a href="http://docs.maadix.net/vpn/" target="_blank"><?php printf(_("VPN"));?></a></li>

                                    </ul>
                                </li>

                          </ul>

                        </nav>
                    <?php }?>
                    </div>
                    <footer class="sidebar-footer">
                        <ul class="nav metismenu" id="customize-menu">
                              <li>
                                <?php require_once 'classes/class.locale.php';
                                $locale = new CpanelLocale();?>

                                <?php echo $locale->locale_select();?>
                              </li>

                            <li>
                                <ul>
                                    <li class="customize">
                                        <div class="customize-item">
                                            <div class="row customize-header">
                                                <div class="col-xs-4"> </div>
                                                <div class="col-xs-4"> <label class="title">fixed</label> </div>
                                                <div class="col-xs-4"> <label class="title">static</label> </div>
                                            </div>
                                            <div class="row hidden-md-down">
                                                <div class="col-xs-4"> <label class="title">Sidebar:</label> </div>
                                                <div class="col-xs-4"> <label>
                                                        <input class="radio" type="radio" name="sidebarPosition" value="sidebar-fixed" >
                                                        <span></span>
                                                    </label> </div>
                                                <div class="col-xs-4"> <label>
                                                        <input class="radio" type="radio" name="sidebarPosition" value="">
                                                        <span></span>
                                                    </label> </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xs-4"> <label class="title">Header:</label> </div>
                                                <div class="col-xs-4"> <label>
                                                        <input class="radio" type="radio" name="headerPosition" value="header-fixed">
                                                        <span></span>
                                                    </label> </div>
                                                <div class="col-xs-4"> <label>
                                                        <input class="radio" type="radio" name="headerPosition" value="">
                                                        <span></span>
                                                    </label> </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xs-4"> <label class="title">Footer:</label> </div>
                                                <div class="col-xs-4"> <label>
                                                        <input class="radio" type="radio" name="footerPosition" value="footer-fixed">
                                                        <span></span>
                                                    </label> </div>
                                                <div class="col-xs-4"> <label>
                                                        <input class="radio" type="radio" name="footerPosition" value="">
                                                        <span></span>
                                                    </label> </div>
                                            </div>
                                        </div>
                                        <div class="customize-item">
                                            <ul class="customize-colors">
                                                <li> <span class="color-item color-red" data-theme="red"></span> </li>
                                                <li> <span class="color-item color-orange" data-theme="orange"></span> </li>
                                                <li> <span class="color-item color-green active" data-theme=""></span> </li>
                                                <li> <span class="color-item color-seagreen" data-theme="seagreen"></span> </li>
                                                <li> <span class="color-item color-blue" data-theme="blue"></span> </li>
                                                <li> <span class="color-item color-purple" data-theme="purple"></span> </li>
                                            </ul>
                                        </div>
                                    </li>
                                </ul>
                                <a href=""> <i class="fa fa-cog"></i> Customize </a>
                            </li>
                        </ul>
                    </footer>
                </aside>
<div class="sidebar-overlay" id="sidebar-overlay"></div>