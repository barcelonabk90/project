<header class="navbar navbar-fixed-top navbar-inverse">
  <div class="navbar-inner">
    <div class="container">
      
      <nav>
        <ul class="nav pull-right">
          <li><a href="/project">Home</a></li>
          <li><a href="#">Help</a></li>
         
          <?php if (AuthComponent::user('username')) { ?>
           
                  <li>
                    <?php  echo $this->Html->link('Profile',array('controller'=>'users', 'action'=>'view')); ?>
                  </li>

                  <li>
                    <?php  echo $this->Html->link('Setting',array('controller'=>'users', 'action'=>'edit')); ?>
                  </li>
            
                  <li>
                    <?php  echo $this->Html->link('Logout',array('controller'=>'users', 'action'=>'logout')); ?>
                  </li>
                </ul>
              </li>
          <?php } else{?>
              <li><?php echo $this->Html->link('Login',array('controller'=>'users', 'action'=>'login')); ?></li>
  		<?php } ?>
        </ul>
      </nav>
    </div>
  </div>
</header>
      
          
