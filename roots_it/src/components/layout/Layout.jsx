import Navbar from './Navbar';
import Footer from './Footer';
import ScrollToTopButton from '../common/ScrollToTopButton';

/** App chrome: sticky navbar, page content, footer, back-to-top FAB. */
export default function Layout({ children }) {
  return (
    <>
      <Navbar />
      <main id="main">{children}</main>
      <Footer />
      <ScrollToTopButton />
    </>
  );
}
