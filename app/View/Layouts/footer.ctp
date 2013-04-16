<footer class="footer">
  <small>
    <?php if (AuthComponent::user('userType')==1) { 
     echo $this->Html->link('Admin Manager',array('controller'=>'admin', 'action'=>'index'));
     } ?>

      <?php if (AuthComponent::user('userType')==2) { 
     echo $this->Html->link('Group Manager',array('controller'=>'groups', 'action'=>'index'));
     } ?>

      <?php if (AuthComponent::user('userType')==3) { 
     echo $this->Html->link('Teacher Page',array('controller'=>'teachers', 'action'=>'index'));
     } ?>

  </small>
  <nav>
    <ul>
      <li><a href="http://hust.edu.vn/web/vi/home">Hanoi University of Science and Technology</a></li>
    </ul>
  </nav>
</footer>