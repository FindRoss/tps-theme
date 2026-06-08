import React from 'react';
import { createRoot } from '@wordpress/element';
import Menu from './menu/Menu';

import "../assets/css/style.scss";

const root = createRoot(document.getElementById('sidebar-navigation'));
root.render(<Menu />);
