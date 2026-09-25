import { FiStar } from 'react-icons/fi';

/** Single testimonial. `item` = { quote, name, role, company, rating, avatar } */
export default function TestimonialCard({ item }) {
  return (
    <figure className="tst-card">
      <div className="tst-card__stars" aria-label={`${item.rating} out of 5 stars`}>
        {Array.from({ length: item.rating }).map((_, i) => (
          <FiStar key={i} fill="currentColor" />
        ))}
      </div>
      <blockquote className="tst-card__quote">“{item.quote}”</blockquote>
      <figcaption className="tst-card__person">
        {item.avatar && (
          <img
            className="tst-card__avatar"
            src={item.avatar}
            alt={item.name}
            loading="lazy"
          />
        )}
        <span>
          <span className="tst-card__name">{item.name}</span>
          <br />
          <span className="tst-card__role">
            {[item.role, item.company].filter(Boolean).join(', ')}
          </span>
        </span>
      </figcaption>
    </figure>
  );
}
