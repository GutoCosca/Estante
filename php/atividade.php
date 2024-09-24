<?php
    class Atividade {
        private $last_time;

        public function getLast_time() {
                return $this->last_time;
        }

        public function setLast_time($last_time) {
                $this->last_time = $last_time;
        }
        
        public function tempo() {
            $this->getLast_time($_SESSION['last_time']);
            if (isset($_SESSION['last_time']) && (time() - $_SESSION['last_time'] > 1800)) {
                session_unset();
                session_destroy();
                header('location:principal.html');
            }
            $_SESSION['last_time'] = time();
        }
    }
?>