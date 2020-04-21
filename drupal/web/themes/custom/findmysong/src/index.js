// Our custom SASS.
import './scss/styles.scss';

// Process twig components.
function requireAll(require) {
  require.keys().forEach(require);
}
requireAll(require.context('./components/', true, /\.twig$/));
