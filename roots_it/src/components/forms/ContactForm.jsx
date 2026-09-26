import { useState } from 'react';
import { FiSend } from 'react-icons/fi';
import Button from '../common/Button';
import { useSection } from '../../hooks/useApi';
import { api } from '../../lib/api';
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

// Server-side validation errors arrive as { field: [messages] }.
const firstErrors = (errors = {}) =>
  Object.fromEntries(Object.entries(errors).map(([field, messages]) => [field, messages[0]]));

/**
 * Controlled contact form with client-side validation. Submissions are sent
 * to the backend (`POST /api/it/contact`) and appear under IT → Enquiries in
 * the admin panel.
 */
export default function ContactForm() {
  const [values, setValues] = useState(initial);
  const [errors, setErrors] = useState({});
  const [status, setStatus] = useState('idle'); // idle | submitting | success | error
  const [serverMessage, setServerMessage] = useState('');
  const serviceOptions = useSection('contact_service_options').items.map((o) => o.title);
  const budgetOptions = useSection('contact_budget_options').items.map((o) => o.title);

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
      const { message } = await api.contact(values);
      setServerMessage(message);
      setStatus('success');
      setValues(initial);
    } catch (error) {
      setErrors(firstErrors(error.errors));
      setServerMessage(
        error.status === 429
          ? 'Too many messages in a short time. Please wait a minute and try again.'
          : error.errors
            ? 'Please fix the highlighted fields.'
            : 'We could not send your message right now. Please try again or email us directly.'
      );
      setStatus('error');
    }
  };

  const field = (name) =>
    classNames('field', errors[name] && 'field--error');

  return (
    <form className="form" onSubmit={handleSubmit} noValidate>
      {status === 'success' && (
        <p className="form__status form__status--ok" role="status">
          {serverMessage}
        </p>
      )}
      {status === 'error' && (
        <p className="form__status form__status--error" role="alert">
          {serverMessage}
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
