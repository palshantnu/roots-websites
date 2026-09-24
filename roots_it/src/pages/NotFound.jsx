import SEO from '../components/common/SEO';
import Button from '../components/common/Button';

export default function NotFound() {
  return (
    <>
      <SEO title="Page not found" noindex />
      <section className="notfound">
        <div className="container">
          <span className="notfound__code">404</span>
          <h1 style={{ marginBottom: 12 }}>This page took a wrong turn</h1>
          <p className="lead mx-auto" style={{ marginBottom: 26 }}>
            The page you’re looking for doesn’t exist or has moved. Let’s get you
            back on track.
          </p>
          <div className="btn-row" style={{ justifyContent: 'center' }}>
            <Button to="/">Back to Home</Button>
            <Button to="/contact" variant="outline">
              Contact Us
            </Button>
          </div>
        </div>
      </section>
    </>
  );
}
