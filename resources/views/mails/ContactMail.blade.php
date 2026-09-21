<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Customer Contact Form {{ general()->title }}</title>
  </head>
  <body style="margin:0; padding:20px 0; background:#f4f4f4; font-family:'Kanit', Arial, sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f4f4; padding:20px 0;">
      <tr>
        <td align="center">
          <table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff; border-radius:10px; overflow:hidden; box-shadow:0 2px 10px rgba(0,0,0,0.1);">
            
            <!-- Header -->
            <tr>
              <td align="center" style="background:#ff005e; padding:20px;">
                <img src="{{ URL::asset(general()->logo()) }}" alt="Logo" style="max-width:150px; display:block; margin-bottom:10px;">
                <h2 style="margin:0; color:#ffffff; font-weight:400;">Customer Contact Details</h2>
              </td>
            </tr>

            <!-- Body -->
            <tr>
              <td style="padding:30px; color:#333333;">
                <p style="font-size:16px; margin-bottom:15px; border-bottom:2px solid #f1f1f1; padding-bottom:8px; font-weight:600;">Contact Information</p>

                <table width="100%" cellpadding="5" cellspacing="0" style="border-collapse:collapse;">
                  <tr>
                    <td width="30%" style="font-weight:600; color:#555;">Name:</td>
                    <td>{{ $datas['r']['name'] }}</td>
                  </tr>
                  <tr>
                    <td style="font-weight:600; color:#555;">Phone:</td>
                    <td>{{ $datas['r']['phone'] }}</td>
                  </tr>
                  <tr>
                    <td style="font-weight:600; color:#555;">Email:</td>
                    <td>{{ $datas['r']['email'] }}</td>
                  </tr>
                  <tr>
                    <td style="font-weight:600; color:#555;">Subject:</td>
                    <td>{{ $datas['r']['subject'] }}</td>
                  </tr>
                </table>

                <div style="margin-top:20px; background:#f9f9f9; padding:15px; border-left:4px solid #0078D4; border-radius:6px;">
                  {!! $datas['r']['message'] !!}
                </div>
              </td>
            </tr>

            <!-- Footer -->
            <tr>
              <td align="center" style="background:#fafafa; padding:15px; color:#888; font-size:12px;">
                &copy; {{ date('Y') }} {{ general()->title }}. All rights reserved.
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
  </body>
</html>
