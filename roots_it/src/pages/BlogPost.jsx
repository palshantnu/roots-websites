import { Link, useParams } from 'react-router-dom';
import { FiArrowLeft } from 'react-icons/fi';
import SEO from '../components/common/SEO';
import Reveal from '../components/common/Reveal';
import Badge from '../components/common/Badge';
import Loader from '../components/common/Loader';
import AsyncState from '../components/common/AsyncState';
import SmartImage from '../components/common/SmartImage';
import BlogCard from '../components/cards/BlogCard';
import CTASection from '../components/sections/CTASection';
import SectionTitle from '../components/common/SectionTitle';
import NotFound from './NotFound';
import { usePosts } from '../hooks/useApi';
import { parseBody } from '../utils/content';
import { formatDate, readingTime } from '../utils/helpers';

/** Render one content block parsed from the article body. */
function Block({ block }) {
  if (block.type === 'h2') return <h2>{block.text}</h2>;
  if (block.type === 'h3') return <h3>{block.text}</h3>;
  if (block.type === 'ul')
    return (
      <ul>
        {block.items.map((it) => (
          <li key={it}>{it}</li>
        ))}
      </ul>
    );
  return <p>{block.text}</p>;
}

export default function BlogPost() {
  const { slug } = useParams();
  const { data: blogs, loading, error, reload } = usePosts();
  const post = blogs.find((b) => b.slug === slug);

  if (!post) {
    if (loading) return <Loader />;
    if (error) {
      return (
        <section className="section">
          <div className="container container--narrow">
            <AsyncState error={error} empty onRetry={reload} errorText="We couldn’t load this article right now." />
          </div>
        </section>
      );
    }
    return <NotFound />;
  }

  const minutes = readingTime(post.body);
  const related = blogs
    .filter((b) => b.slug !== post.slug && b.category === post.category)
    .slice(0, 3);
  const fallbackRelated = blogs.filter((b) => b.slug !== post.slug).slice(0, 3);
  const relatedPosts = related.length ? related : fallbackRelated;

  return (
    <>
      <SEO title={post.title} description={post.excerpt} type="article" image={post.image} />

      <article className="section">
        <div className="container post">
          <Link to="/blog" className="link-arrow" style={{ marginBottom: 18 }}>
            <FiArrowLeft /> All articles
          </Link>

          <div className="chip-row" style={{ margin: '10px 0 14px' }}>
            <Badge>{post.category}</Badge>
            <Badge variant="soft">{formatDate(post.publishedAt)}</Badge>
            <Badge variant="soft">{minutes}</Badge>
          </div>

          <h1>{post.title}</h1>
          {post.author && (
            <p className="lead" style={{ marginTop: 12 }}>
              By {post.author}
            </p>
          )}

          <div className="post__cover">
            <SmartImage src={post.image} alt={post.title} />
          </div>

          <Reveal className="post__body">
            {parseBody(post.body).map((block, i) => (
              <Block key={i} block={block} />
            ))}
          </Reveal>
        </div>
      </article>

      {relatedPosts.length > 0 && (
        <section className="section section--muted">
          <div className="container">
            <SectionTitle eyebrow="Keep reading" title="Related articles" align="center" />
            <div className="grid grid-3">
              {relatedPosts.map((p) => (
                <BlogCard key={p.id} post={p} />
              ))}
            </div>
          </div>
        </section>
      )}

      <CTASection
        title="Working on something similar?"
        primary={{ label: 'Start Your Project', to: '/contact' }}
        secondary={{ label: 'Contact Us', to: '/contact' }}
      />
    </>
  );
}
