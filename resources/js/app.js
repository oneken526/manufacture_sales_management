import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// jQuery をグローバルに公開（Blade テンプレートからも使用可能）
import $ from 'jquery';
window.$ = $;
window.jQuery = $;
