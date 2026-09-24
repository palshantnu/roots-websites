import { useState } from 'react'
import { ArrowRight, Check, Upload } from 'lucide-react'
import { api } from '../api'
import { useCategories } from '../hooks'

const emptyFields = { full_name: '', email: '', phone: '', location: '', book_title: '', genre: '', language: 'English', word_count: '', formats: [] }

export default function PublishPage({ Layout, PageIntro }) {
  const { categories } = useCategories()
  const [step, setStep] = useState(1)
  const [fields, setFields] = useState(emptyFields)
  const [manuscript, setManuscript] = useState(null)
  const [agreed, setAgreed] = useState(false)
  const [done, setDone] = useState(false)
  const [loading, setLoading] = useState(false)
  const [error, setError] = useState(null)

  const set = (key, value) => setFields(current => ({ ...current, [key]: value }))
  const toggleFormat = format => setFields(current => ({
    ...current,
    formats: current.formats.includes(format) ? current.formats.filter(f => f !== format) : [...current.formats, format],
  }))

  const next = async event => {
    event.preventDefault()
    if (step < 5) {
      setStep(step + 1)
      return
    }
    setLoading(true)
    setError(null)
    try {
      const payload = new FormData()
      Object.entries(fields).forEach(([key, value]) => {
        if (key === 'formats') value.forEach(format => payload.append('formats[]', format))
        else payload.append(key, value)
      })
      if (manuscript) payload.append('manuscript', manuscript)
      await api.submitPublishEnquiry(payload)
      setDone(true)
    } catch (err) {
      setError(err.message || 'Something went wrong. Please try again.')
    } finally {
      setLoading(false)
    }
  }

  return <Layout><main className="publish-page"><PageIntro eyebrow="PUBLISH WITH RTS" title="Your first step starts here." copy="Tell us about your book. We will help you find the right publishing path." />{done ? <div className="success-state"><Check size={44} /><h2>Enquiry received.</h2><p>Thank you. A publishing advisor will contact you within one working day.</p><a className="button" href="/">Back to home <ArrowRight size={16} /></a></div> : <form className="wizard" onSubmit={next}><div className="steps">{['You', 'Your book', 'Preferences', 'Manuscript', 'Review'].map((label, index) => <span className={index + 1 <= step ? 'current' : ''} key={label}><b>{index + 1}</b>{label}</span>)}</div><div className="wizard-panel">{step === 1 && <><h2>Tell us about yourself.</h2><div className="form-grid"><input required value={fields.full_name} onChange={e => set('full_name', e.target.value)} placeholder="Full name" /><input required type="email" value={fields.email} onChange={e => set('email', e.target.value)} placeholder="Email address" /><input required value={fields.phone} onChange={e => set('phone', e.target.value)} placeholder="Phone number" /><input value={fields.location} onChange={e => set('location', e.target.value)} placeholder="City & country" /></div></>}{step === 2 && <><h2>Tell us about your book.</h2><div className="form-grid"><input required value={fields.book_title} onChange={e => set('book_title', e.target.value)} placeholder="Book title" /><select required value={fields.genre} onChange={e => set('genre', e.target.value)}><option value="" disabled>Choose a genre</option>{categories.map(item => <option key={item}>{item}</option>)}</select><select value={fields.language} onChange={e => set('language', e.target.value)}><option>English</option><option>Hindi</option><option>Other Indian language</option></select><input value={fields.word_count} onChange={e => set('word_count', e.target.value)} placeholder="Approximate word count" /></div></>}{step === 3 && <><h2>How would you like to publish?</h2><div className="check-grid">{['E-book', 'Paperback', 'Hardcover', 'Print-on-demand', 'Distribution', 'Marketing support'].map(item => <label key={item}><input type="checkbox" checked={fields.formats.includes(item)} onChange={() => toggleFormat(item)} />{item}</label>)}</div></>}{step === 4 && <><h2>Share your manuscript.</h2><label className="upload-box"><Upload size={30} /><strong>{manuscript ? manuscript.name : 'Upload a manuscript'}</strong><span>PDF, DOC or DOCX up to 20MB</span><input type="file" accept=".pdf,.doc,.docx" onChange={e => setManuscript(e.target.files[0] || null)} /></label></>}{step === 5 && <><h2>Review your enquiry.</h2><p className="review-copy">Your information is ready to send. Our team will review your requirements and reply with a tailored recommendation.</p>{error && <p className="form-error">{error}</p>}<label><input type="checkbox" required checked={agreed} onChange={e => setAgreed(e.target.checked)} /> I agree to be contacted about my publishing enquiry.</label></>}<div className="wizard-actions">{step > 1 && <button type="button" className="text-button" onClick={() => setStep(step - 1)}>← Back</button>}<button className="button" type="submit" disabled={loading}>{loading ? 'Sending...' : step === 5 ? 'Submit enquiry' : 'Continue'}<ArrowRight size={16} /></button></div></div></form>}</main></Layout>
}
