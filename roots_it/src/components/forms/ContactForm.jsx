import { useState } from 'react';
import { FiSend } from 'react-icons/fi';
import Button from '../common/Button';
import { serviceOptions, budgetOptions } from '../../data/faqs';
import { classNames } from '../../utils/helpers';

const initial = {
  name: '',
  email: '',
  phone: '',
  company: '',
  service: '',
  budget: '',
  message: '',
};

function validate(values) {
  const errors = {};
  if (!values.name.trim()) errors.name = 'Please enter your name.';
  if (!values.email.trim()) errors.email = 'Please enter your email.';
  else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(values.email))
    errors.email = 'Please enter a valid email address.';
  if (!values.service) errors.service = 'Please choose a service.';
  if (!values.message.trim() || values.message.trim().length < 10)
    errors.message = 'Tell us a little more (10+ characters).';
  return errors;
}

/**
 * Controlled contact form with client-side validation and a mocked submit.
 * `onSubmit` receives the values object — swap the timeout for a real
 * `fetch('/api/leads', …)` when the backend exists.
 */
export default function ContactForm({ onSubmit }) {
  const [values, setValues] = useState(initial);
  const [errors, setErrors] = useState({});
  const [status, setStatus] = useState('idle'); // idle | submitting | success

  const handleChange = (e) => {
    const { name, value } = e.target;
    setValues((v) => ({ ...v, [name]: value }));
    if (errors[name]) setErrors((err) => ({ ...err, [name]: undefined }));
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    const found = validate(values);
    setErrors(found);
    if (Object.keys(found).length > 0) return;

    setStatus('submitting');
    try {
      if (onSubmit) await onSubmit(values);
      else await new Promise((res) => setTimeout(res, 900)); // mock network
      setStatus('success');
      setValues(initial);
    } catch {
      setStatus('idle');
    }
  };

  const field = (name) =>
    classNames('field', errors[name] && 'field--error');

  return (
    <form className="form" onSubmit={handleSubmit} noValidate>
      {status === 'success' && (
        <p className="form__status form__status--ok" role="status">
          Thanks — your message is in. A specialist will get back to you within one business day.
        </p>
      )}

      <div className="form__row">
        <div className={field('name')}>
          <label htmlFor="name">Full name *</label>
          <input id="name" name="name" value={values.name} onChange={handleChange} autoComplete="name" />
          {errors.name && <span className="field__error">{errors.name}</span>}
        </div>
        <div className={field('email')}>
          <label htmlFor="email">Email *</label>
          <input id="email" name="email" type="email" value={values.email} onChange={handleChange} autoComplete="email" />
          {errors.email && <span className="field__error">{errors.email}</span>}
        </div>
      </div>

      <div className="form__row">
        <div className="field">
          <label htmlFor="phone">Phone number</label>
          <input id="phone" name="phone" value={values.phone} onChange={handleChange} autoComplete="tel" />
        </div>
        <div className="field">
          <label htmlFor="company">Company name</label>
          <input id="company" name="company" value={values.company} onChange={handleChange} autoComplete="organization" />
        </div>
      </div>

      <div className="form__row">
        <div className={field('service')}>
          <label htmlFor="service">Service required *</label>
          <select id="service" name="service" value={values.service} onChange={handleChange}>
            <option value="">Select a service…</option>
            {serviceOptions.map((o) => (
              <option key={o} value={o}>
                {o}
              </option>
            ))}
          </select>
          {errors.service && <span className="field__error">{errors.service}</span>}
        </div>
        <div className="field">
          <label htmlFor="budget">Project budget</label>
          <select id="budget" name="budget" value={values.budget} onChange={handleChange}>
            <option value="">Select a range…</option>
            {budgetOptions.map((o) => (
              <option key={o} value={o}>
                {o}
              </option>
            ))}
          </select>
        </div>
      </div>

      <div className={field('message')}>
        <label htmlFor="message">Project details *</label>
        <textarea
          id="message"
          name="message"
          value={values.message}
          onChange={handleChange}
          placeholder="What are you trying to build or achieve? Timelines, goals, links — anything helps."
        />
        {errors.message && <span className="field__error">{errors.message}</span>}
      </div>

      <Button type="submit" size="lg" disabled={status === 'submitting'}>
        <FiSend />
        {status === 'submitting' ? 'Sending…' : "Let's Discuss Your Project"}
      </Button>
    </form>
  );
}
