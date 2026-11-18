<?php
    class Atividade {
        private $last_time;

        public function getLast_time() {
                return $this->last_time;
        }

        public function setLast_time($last_time) {
                $this->last_time = $last_time;
        }

        // Encerra a sessão após 30 minutos sem atividade
        public function tempo() {
            if (isset($_SESSION['last_time'])) {
                $this->setLast_time($_SESSION['last_time']);
            }
            if ($this->getLast_time() != null && (time() - $this->getLast_time() > 1800)) {
                logout();
            }
            $_SESSION['last_time'] = time();
        }
    }
?>
