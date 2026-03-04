import './bootstrap';

import Alpine from 'alpinejs';
import focus from '@alpinejs/focus';
import { authModal } from './components/authModal';

window.Alpine = Alpine;
window.authModal = authModal;

Alpine.plugin(focus);
Alpine.start();
