import { useState } from 'react'
import { ArrowRight } from 'lucide-react'
import { api } from '../api'

/**
 * Enquiry form shown in the home hero (in place of the book illustration).
 * Posts to the publication contact endpoint (name/email/message required).
 */
export default function HomeEnquiry() {
  const [form, setForm] = useState({ name: '', email: '', phone: '', subject: '', message: '' })
  const [status, setStatus] = useState('idle') // idle | sending | done | error
  const [error, setError] = useState('')

  const update = field => event => setForm(prev => ({ ...prev, [field]: event.target.value }))

  const submit = async event => {
    event.preventDefault()
    setStatus('sending')
    setError('')
    try {
      await api.submitContact(form)
      setStatus('done')
      setForm({ name: '', email: '', phone: '', subject: '', message: '' })
    } catch (err) {
      setStatus('error')
      setError(err?.message || 'Something went wrong. Please try again.')
    }
  }

  if (status === 'done') {
    return (
      <div className="home-enquiry">
        <div className="home-enquiry-done">
          <h3>Thank you!</h3>
          <p>We’ve received your enquiry and will get back to you within one working day.</p>
          <button type="button" onClick={() => setStatus('idle')}>Send another</button>
        </div>
      </div>
    )
  }

  return (
    <div className="home-enquiry">
      <form className="home-enquiry-card" onSubmit={submit}>
        <span className="eyebrow">LET’S TALK</span>
        <h3>Enquire about publishing</h3>
        <p>Tell us about your book or project — we’ll guide you through the next step.</p>

        <input type="text" required placeholder="Your name" value={form.name} onChange={update('name')} />
        <input type="email" required placeholder="Email address" value={form.email} onChange={update('email')} />
        <input type="tel" placeholder="Phone (optional)" value={form.phone} onChange={update('phone')} />
        <input type="text" placeholder="Subject (optional)" value={form.subject} onChange={update('subject')} />
        <textarea required rows={3} placeholder="How can we help?" value={form.message} onChange={update('message')} />

        {status === 'error' && <p className="home-enquiry-error">{error}</p>}

        <button type="submit" disabled={status === 'sending'}>
          {status === 'sending' ? 'Sending…' : <>Send enquiry <ArrowRight size={16} /></>}
        </button>
      </form>
    </div>
  )
}
