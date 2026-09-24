import { useState } from "react";
import { motion } from "framer-motion";
import { Send, Zap, Lock, HeadphonesIcon, Mail, Phone, User, MapPin, MessageSquare } from "lucide-react";
import Section from "../ui/Section";
import SectionHeading from "../ui/SectionHeading";
import Reveal from "../ui/Reveal";
import Button from "../ui/Button";
import { countryCodes } from "../../data/countryCodes";
import { useToast } from "../../context/ToastContext";

const initialForm = {
  fullName: "",
  email: "",
  countryCode: "+91",
  phone: "",
  subject: "",
  city: "",
};

const sidePanel = [
  { icon: Zap, title: "Quick Response", description: "We reply within 24 hours, every time." },
  { icon: Lock, title: "Secure & Private", description: "Your details never leave our NDA-protected systems." },
  { icon: HeadphonesIcon, title: "Expert Support", description: "Talk directly to a subject-matter mentor, not a call centre." },
];

function validate(form) {
  const errors = {};
  if (!form.fullName.trim() || form.fullName.trim().length < 2) {
    errors.fullName = "Please enter your full name.";
  }
  if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.email)) {
    errors.email = "Please enter a valid email address.";
  }
  if (!/^\d{7,12}$/.test(form.phone.replace(/\s/g, ""))) {
    errors.phone = "Enter a valid contact number (7–12 digits).";
  }
  if (!form.subject.trim()) {
    errors.subject = "Tell us what you need help with.";
  }
  if (!form.city.trim()) {
    errors.city = "Please enter your city.";
  }
  return errors;
}

export default function ContactForm() {
  const [form, setForm] = useState(initialForm);
  const [errors, setErrors] = useState({});
  const [submitting, setSubmitting] = useState(false);
  const { addToast } = useToast();

  const handleChange = (field) => (e) => {
    setForm((f) => ({ ...f, [field]: e.target.value }));
    if (errors[field]) setErrors((err) => ({ ...err, [field]: undefined }));
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    const validationErrors = validate(form);
    setErrors(validationErrors);
    if (Object.keys(validationErrors).length > 0) {
      addToast("Please fix the highlighted fields before submitting.", "error");
      return;
    }
    setSubmitting(true);
    // No backend wired up yet — log the payload and confirm via toast.
    console.log("Contact form submitted:", form);
    setTimeout(() => {
      setSubmitting(false);
      addToast(`Thanks, ${form.fullName.split(" ")[0]}! Our team will reach out within 24 hours.`);
      setForm(initialForm);
    }, 700);
  };

  const fieldClass = (field) =>
    `w-full rounded-xl border bg-white px-4 py-3 text-sm text-ink-800 placeholder:text-ink-400 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-400/40 dark:bg-white/5 dark:text-ivory-100 ${
      errors[field]
        ? "border-red-400 focus:border-red-500"
        : "border-ink-900/15 focus:border-blue-400 dark:border-white/10"
    }`;

  return (
    <Section id="contact" className="bg-ivory-100/60 dark:bg-ink-900/20">
      <SectionHeading
        eyebrow="Get In Touch"
        eyebrowIcon={MessageSquare}
        title="Tell Us About Your Research"
        description="Share a few details and a mentor matched to your subject area will reach out with next steps."
      />

      <div className="mt-14 grid grid-cols-1 gap-8 lg:grid-cols-5">
        <Reveal delay={0.1} className="lg:col-span-3">
          <form
            onSubmit={handleSubmit}
            noValidate
            className="rounded-3xl border border-ink-900/10 bg-white p-6 shadow-xl shadow-ink-900/5 sm:p-8 dark:border-white/10 dark:bg-white/[0.04]"
          >
            <div className="grid grid-cols-1 gap-5 sm:grid-cols-2">
              <div className="sm:col-span-2">
                <label htmlFor="fullName" className="mb-1.5 flex items-center gap-1.5 text-sm font-semibold text-ink-700 dark:text-ivory-200">
                  <User className="h-4 w-4 text-blue-500" aria-hidden="true" /> Full Name
                </label>
                <input
                  id="fullName"
                  type="text"
                  autoComplete="name"
                  value={form.fullName}
                  onChange={handleChange("fullName")}
                  placeholder="Jordan Alvarez"
                  aria-invalid={Boolean(errors.fullName)}
                  aria-describedby={errors.fullName ? "fullName-error" : undefined}
                  className={fieldClass("fullName")}
                />
                {errors.fullName && (
                  <p id="fullName-error" className="mt-1.5 text-xs font-medium text-red-500">
                    {errors.fullName}
                  </p>
                )}
              </div>

              <div className="sm:col-span-2">
                <label htmlFor="email" className="mb-1.5 flex items-center gap-1.5 text-sm font-semibold text-ink-700 dark:text-ivory-200">
                  <Mail className="h-4 w-4 text-blue-500" aria-hidden="true" /> Email Address
                </label>
                <input
                  id="email"
                  type="email"
                  autoComplete="email"
                  value={form.email}
                  onChange={handleChange("email")}
                  placeholder="you@university.edu"
                  aria-invalid={Boolean(errors.email)}
                  aria-describedby={errors.email ? "email-error" : undefined}
                  className={fieldClass("email")}
                />
                {errors.email && (
                  <p id="email-error" className="mt-1.5 text-xs font-medium text-red-500">
                    {errors.email}
                  </p>
                )}
              </div>

              <div className="sm:col-span-2">
                <label htmlFor="phone" className="mb-1.5 flex items-center gap-1.5 text-sm font-semibold text-ink-700 dark:text-ivory-200">
                  <Phone className="h-4 w-4 text-blue-500" aria-hidden="true" /> Contact Number
                </label>
                <div className="flex gap-2">
                  <select
                    aria-label="Country code"
                    value={form.countryCode}
                    onChange={handleChange("countryCode")}
                    className="w-28 rounded-xl border border-ink-900/15 bg-white px-2 py-3 text-sm text-ink-700 focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-400/40 dark:border-white/10 dark:bg-white/5 dark:text-ivory-100"
                  >
                    {countryCodes.map((c) => (
                      <option key={c.code} value={c.code}>
                        {c.flag} {c.code}
                      </option>
                    ))}
                  </select>
                  <input
                    id="phone"
                    type="tel"
                    autoComplete="tel-national"
                    value={form.phone}
                    onChange={handleChange("phone")}
                    placeholder="98765 43210"
                    aria-invalid={Boolean(errors.phone)}
                    aria-describedby={errors.phone ? "phone-error" : undefined}
                    className={fieldClass("phone")}
                  />
                </div>
                {errors.phone && (
                  <p id="phone-error" className="mt-1.5 text-xs font-medium text-red-500">
                    {errors.phone}
                  </p>
                )}
              </div>

              <div>
                <label htmlFor="subject" className="mb-1.5 block text-sm font-semibold text-ink-700 dark:text-ivory-200">
                  Subject
                </label>
                <input
                  id="subject"
                  type="text"
                  value={form.subject}
                  onChange={handleChange("subject")}
                  placeholder="e.g. Data Analysis"
                  aria-invalid={Boolean(errors.subject)}
                  aria-describedby={errors.subject ? "subject-error" : undefined}
                  className={fieldClass("subject")}
                />
                {errors.subject && (
                  <p id="subject-error" className="mt-1.5 text-xs font-medium text-red-500">
                    {errors.subject}
                  </p>
                )}
              </div>

              <div>
                <label htmlFor="city" className="mb-1.5 flex items-center gap-1.5 text-sm font-semibold text-ink-700 dark:text-ivory-200">
                  <MapPin className="h-4 w-4 text-blue-500" aria-hidden="true" /> City
                </label>
                <input
                  id="city"
                  type="text"
                  autoComplete="address-level2"
                  value={form.city}
                  onChange={handleChange("city")}
                  placeholder="Bengaluru"
                  aria-invalid={Boolean(errors.city)}
                  aria-describedby={errors.city ? "city-error" : undefined}
                  className={fieldClass("city")}
                />
                {errors.city && (
                  <p id="city-error" className="mt-1.5 text-xs font-medium text-red-500">
                    {errors.city}
                  </p>
                )}
              </div>
            </div>

            <Button type="submit" size="lg" icon={Send} className="mt-7 w-full sm:w-auto" disabled={submitting}>
              {submitting ? "Sending..." : "Send Message"}
            </Button>
          </form>
        </Reveal>

        <Reveal delay={0.2} className="lg:col-span-2">
          <div className="flex h-full flex-col gap-5 rounded-3xl border-2 border-blue-200 bg-gradient-paper p-6 text-ink-950 shadow-xl shadow-blue-500/10 dark:border-white/10 dark:bg-gradient-ink dark:text-ivory-50 dark:shadow-ink-950/20 sm:p-8">
            <h3 className="font-display text-xl font-semibold sm:text-2xl">Why reach out to us?</h3>
            {sidePanel.map((item, i) => (
              <motion.div
                key={item.title}
                initial={{ opacity: 0, x: 20 }}
                whileInView={{ opacity: 1, x: 0 }}
                viewport={{ once: true }}
                transition={{ delay: i * 0.1 }}
                className="flex items-start gap-3 rounded-2xl bg-blue-50 p-4 dark:bg-white/5"
              >
                <span className="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-white text-blue-600 shadow-sm dark:bg-white/10 dark:text-blue-300">
                  <item.icon className="h-5 w-5" aria-hidden="true" />
                </span>
                <div>
                  <p className="font-semibold">{item.title}</p>
                  <p className="mt-0.5 text-sm text-ink-600 dark:text-ink-200">{item.description}</p>
                </div>
              </motion.div>
            ))}
            <div className="mt-auto rounded-2xl border border-blue-200 bg-blue-50 p-4 text-sm text-ink-600 dark:border-white/10 dark:bg-white/5 dark:text-ink-200">
              Prefer email? Write to us directly at{" "}
              <a href="mailto:hello@thesiscraftacademy.com" className="font-semibold text-blue-600 underline underline-offset-2 dark:text-blue-300">
                hello@thesiscraftacademy.com
              </a>
            </div>
          </div>
        </Reveal>
      </div>
    </Section>
  );
}
