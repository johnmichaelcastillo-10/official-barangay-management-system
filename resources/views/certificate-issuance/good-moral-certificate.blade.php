<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Certificate of Good Moral</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            padding: 40px;
            line-height: 1.6;
            color: #000;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #004085;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 20px;
            text-transform: uppercase;
            font-weight: bold;
        }
        .header h2 {
            font-size: 14px;
            margin: 4px 0;
        }
        .title {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin: 30px 0 10px;
            text-transform: uppercase;
        }
        .subtext {
            text-align: center;
            margin-bottom: 30px;
            font-size: 13px;
        }
        .content {
            font-size: 13px;
            margin-bottom: 40px;
        }
        .signature-section {
            display: flex;
            justify-content: space-between;
            margin-top: 60px;
        }
        .sign-box {
            text-align: center;
        }
        .sign-box p {
            margin-top: 60px;
            border-top: 1px solid #000;
            display: inline-block;
            padding-top: 5px;
        }
        .footer {
            text-align: center;
            font-size: 10px;
            color: #555;
            margin-top: 50px;
            border-top: 1px solid #004085;
            padding-top: 10px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>Republic of the Philippines</h1>
        <h2>Barangay {{ $barangayName ?? 'Sample Barangay' }}, {{ $cityName ?? 'Sample City' }}</h2>
        <h2>Office of the Barangay Captain</h2>
    </div>

    <div class="title">Certificate of Good Moral Character</div>
    <div class="subtext">
        Certificate No.: <strong>{{ $request->tracking_number }}</strong> &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
        Issued on: <strong>{{ now()->format('F d, Y') }}</strong>
    </div>

    <div class="content">
        <p>This is to certify that <strong>{{ $request->resident->full_name }}</strong>, of legal age and a resident of <strong>{{ $request->resident->full_address }}</strong>, is known to be a person of good moral character.</p>

        <p>Based on the records of this Barangay and to the best of our knowledge, the above-named person has not been involved in any unlawful or immoral activities, and has consistently demonstrated respect for the laws and norms of the community.</p>

        <p>This certificate is issued upon the request of the interested party for purposes of employment, school requirements, or any other legal and official purpose it may serve.</p>
    </div>

    <div class="signature-section">
        <div class="sign-box">
            <p>{{ $barangayCaptain ?? 'Juan Dela Cruz' }}<br><small>Barangay Captain</small></p>
        </div>
        <div class="sign-box">
            <p>{{ $secretary ?? 'Maria Santos' }}<br><small>Barangay Secretary</small></p>
        </div>
    </div>

    <div class="footer">
        This is a system-generated certificate and valid without a signature unless a physical copy is required.
    </div>

</body>
</html>
