import { Suspense } from 'react';
import { useRoutes } from 'react-router-dom';
import Layout from './components/layout/Layout.jsx';
import ScrollToTop from './components/common/ScrollToTop.jsx';
import Loader from './components/common/Loader.jsx';
import { routes } from './routes.jsx';

/**
 * App shell: global layout (Navbar + Footer), route-change scroll reset,
 * and Suspense boundary for lazy-loaded pages.
 */
export default function App() {
  const element = useRoutes(routes);

  return (
    <Layout>
      <ScrollToTop />
      <Suspense fallback={<Loader />}>{element}</Suspense>
    </Layout>
  );
}
