import Reveal from '../common/Reveal';

/**
 * Vertical numbered timeline. `steps` = [{ title, description }].
 * Works on light and `.section--dark` backgrounds.
 */
export default function ProcessTimeline({ steps = [] }) {
  return (
    <div className="timeline">
      {steps.map((step, i) => (
        <Reveal className="timeline__row" key={step.title} delay={i * 0.05}>
          <div className="timeline__marker">
            <span className="timeline__num">{String(i + 1).padStart(2, '0')}</span>
            <span className="timeline__line" />
          </div>
          <div className="timeline__body">
            <h3>{step.title}</h3>
            <p>{step.description}</p>
          </div>
        </Reveal>
      ))}
    </div>
  );
}
