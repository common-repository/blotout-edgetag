import React, { useState } from 'react'
import axios from 'axios'
import TextBox from '../components/TextBox'
import NoticeBox from '../components/NoticeBox'

declare var ajaxurl: string
declare var wcbe_nonce: string

interface DataItem {
  name: string
  value: string
}

interface WPJSONResponse {
  data: string
  success: boolean
}

interface SettingsPageProps {
  data: DataItem[] | null
}

const SettingsPage: React.FC<SettingsPageProps> = ({ data }) => {
  const [inputValues, setInputValues] = useState(
    data
      ? data.reduce((acc, item) => {
          return { ...acc, ...item }
        }, {} as { [key: string]: string })
      : {}
  )

  const [showNotice, setShowNotice] = useState(false)
  const [noticeText, setNoticeText] = useState('')
  const [noticeClass, setNoticeClass] = useState('')

  const handleInputChange = (
    event: React.ChangeEvent<HTMLInputElement | HTMLTextAreaElement>
  ) => {
    const name = event.target.name
    const newValue = event.target.value

    setInputValues((prevValues) => ({ ...prevValues, [name]: newValue }))
  }

  const handleCheckboxChange = (event: React.ChangeEvent<HTMLInputElement>) => {
    const name = event.target.name
    const newValue = event.target.checked ? '1' : '0'

    setInputValues((prevValues) => ({ ...prevValues, [name]: newValue }))
  }

  const handleNoticeClose = () => {
    setShowNotice(false)
  }

  const handleSave = async (e: React.MouseEvent<HTMLButtonElement>) => {
    const target = e.target as HTMLButtonElement
    target.classList.add('loading')

    axios
      .post<WPJSONResponse>(
        ajaxurl,
        {
          nonce: wcbe_nonce,
          action: 'set_edgetag_options',
          data: inputValues,
        },
        {
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        }
      )
      .then(
        (response) => {
          target.classList.remove('loading')
          setNoticeText(response.data.data)
          setNoticeClass('updated')
          setShowNotice(true)
        },
        (error) => {
          target.classList.remove('loading')
          setNoticeText(error.response.data.data)
          setNoticeClass('error')
          setShowNotice(true)
        }
      )
  }

  return (
    <div className='setting-page'>
      <div className='setting-page-content'>
        <TextBox>
          <div style={{ maxWidth: '600px' }}>
            <h2>Settings</h2>
            <label>
              <div className='input-label'>EdgeTag URL</div>
              <input
                name='edgetag_url'
                onChange={handleInputChange}
                value={inputValues?.edgetag_url || ''}
                placeholder='e.g. https://xyz.mydomain.com'
                type='text'
              />
            </label>
            <div className='input-description'>
              You can find your URL in{' '}
              <a href='https://app.edgetag.io' target='_blank' rel='noreferrer'>
                https://app.edgetag.io
              </a>{' '}
              under tag details
            </div>
            <label>
              <div className='input-label'>Newsletter selectors</div>
              <input
                name='edgetag_selectors'
                onChange={handleInputChange}
                value={inputValues?.edgetag_selectors || ''}
                type='text'
              />
            </label>
            <div className='input-description'>
              We automatically try to capture newsletter forms, but if its still
              not working please add your own HTML selectors here.
            </div>
            <label>
              <div className='input-label'>Newsletter event name</div>
              <input
                name='edgetag_newsletter_event_name'
                onChange={handleInputChange}
                value={inputValues?.edgetag_newsletter_event_name || ''}
                placeholder='e.g. Lead'
                type='text'
              />
            </label>
            <div className='input-description'>
              By default we would be sending newsletter subscription as Lead
              event. If you want to change the event name, you can do it here.
            </div>
            <label>
              <div className='input-label'>Header Script</div>
              <textarea
                onChange={handleInputChange}
                name='edgetag_script'
                className='code'
                value={inputValues?.edgetag_script || ''}
              />
            </label>
            <div className='input-description'>
              This script will be added to HEAD of every page on your website.
              <br />
              Paste only snippet/s WITHOUT SCRIPT tags.
            </div>
            <div className='input-checkbox'>
              <input
                name='edgetag_purchase_subtotal_only'
                onChange={handleCheckboxChange}
                checked={inputValues?.edgetag_purchase_subtotal_only === '1'}
                type='checkbox'
              />{' '}
              Send only subtotal (exclude shipping and tax) as purchase value
            </div>
            <br />
            <button onClick={handleSave} className='btn'>
              Save
            </button>
            <NoticeBox
              noticeClass={noticeClass}
              isVisible={showNotice}
              onClose={handleNoticeClose}
            >
              <p>{noticeText}</p>
            </NoticeBox>
          </div>
        </TextBox>
      </div>
    </div>
  )
}

export default SettingsPage
