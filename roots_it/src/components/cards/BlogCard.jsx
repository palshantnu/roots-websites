import { Link } from 'react-router-dom';
import { FiArrowRight } from 'react-icons/fi';
import Badge from '../common/Badge';
import SmartImage from '../common/SmartImage';
import { formatDate, readingTime } from '../../utils/helpers';

/** Blog listing card. `post` = an article from the API. */
export default function BlogCard({ post }) {
  const minutes = readingTime(post.body);

  return (
    <article className="card card--hover blog-card">
      <Link to={`/blog/${post.slug}`} className="blog-card__media">
        <SmartImage src={post.image} alt={post.title} loading="lazy" />
      </Link>
      <div className="blog-card__body">
        <div className="blog-card__info">
          <Badge variant="soft">{post.category}</Badge>
          <span>{formatDate(post.publishedAt)}</span>
          <span aria-hidden="true">•</span>
          <span>{minutes}</span>
        </div>
        <h3>
          <Link to={`/blog/${post.slug}`}>{post.title}</Link>
        </h3>
        <p>{post.excerpt}</p>
        <Link to={`/blog/${post.slug}`} className="link-arrow">
          Read more <FiArrowRight />
        </Link>
      </div>
    </article>
  );
}
